<?php

namespace App\Controller\User;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DeviceUsageLog;


class UserDeviceCrudController extends AbstractCrudController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    public static function getEntityFqcn(): string
    {
        return Device::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $device = new Device();
        $device->setUser($this->getUser());

        return $device;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser());
    }

    public function findOneEntity($entityId)
    {
        $device = parent::findOneEntity($entityId);

        if ($device->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $device;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            NumberField::new('powerWatt'),
            BooleanField::new('isTemplate'),
            CollectionField::new('deviceUsageLogs')
                ->useEntryCrudForm(DeviceUsageLogCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $start = Action::new('startUsage', 'Start Usage', 'fa fa-play')
            ->linkToRoute('device_start_usage', fn(Device $device) => ['id' => $device->getId()]);
        $stop = Action::new('stopUsage', 'Stop Usage', 'fa fa-stop')
            ->linkToRoute('device_stop_usage', fn(Device $device) => ['id' => $device->getId()]);

        return $actions
            ->add(Crud::PAGE_INDEX, $start)
            ->add(Crud::PAGE_INDEX, $stop);
    }

    #[Route('/profile/user-device/start-usage/{id}', name: 'device_start_usage')]
    public function startUsage(int $id, DeviceRepository $deviceRepository, EntityManagerInterface $em): RedirectResponse
    {
        $device = $deviceRepository->find($id);

        if (!$device) {
            throw $this->createNotFoundException();
        }
        $log = new DeviceUsageLog();
        $log->setDevice($device);
        $log->setStartedAt(new \DateTimeImmutable());

        $em->persist($log);
        $em->flush();

        $this->addFlash('success', 'Usage started.');

        return $this->redirectToRoute('app_user_dashboard_user_device_index');
    }

    #[Route('/profile/user-device/stop-usage/{id}', name: 'device_stop_usage')]
    public function stopUsage(int $id, DeviceRepository $deviceRepository, EntityManagerInterface $em): RedirectResponse
    {
        $device = $deviceRepository->find($id);

        if (!$device) {
            throw $this->createNotFoundException();
        }

        $repo = $em->getRepository(DeviceUsageLog::class);

        // Get last open session
        $log = $repo->findOneBy([
            'device' => $device,
            'endedAt' => null,
        ], ['startedAt' => 'DESC']);

        if (!$log) {
            $this->addFlash('warning', 'No active usage to stop.');
            return $this->redirectToRoute('app_user_dashboard_user_device_index');
        }

        $log->setEndedAt(new \DateTimeImmutable());
        $log->calculateEnergyUsage(); // if not handled via lifecycle
        $log->setTitleFromData();

        $em->flush();

        $this->addFlash('success', 'Usage stopped.');

        return $this->redirectToRoute('app_user_dashboard_user_device_index');

    }
}
