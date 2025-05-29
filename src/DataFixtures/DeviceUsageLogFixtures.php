<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use DateInterval;
use DateMalformedPeriodStringException;
use DatePeriod;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class DeviceUsageLogFixtures extends Fixture
{
    /**
     * @throws RandomException
     * @throws DateMalformedPeriodStringException
     */
    public function load(ObjectManager $manager): void
    {
        $deviceRepo = $manager->getRepository(Device::class);

        $logs = [
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-20 00:00:00',
                'end' => '2025-05-20 23:59:00',
                'title' => 'Fridge runs all day',
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-20 00:00:00',
                'end' => '2025-05-20 23:59:00',
                'title' => 'Wifi router runs all day',
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-20 07:00:00',
                'end' => '2025-05-20 07:02:00',
                'title' => 'Morning coffee ritual',
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-20 07:05:00',
                'end' => '2025-05-20 07:10:00',
                'title' => 'Heating up breakfast',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-20 07:30:00',
                'end' => '2025-05-20 08:15:00',
                'title' => 'Morning news and weather',
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-20 08:20:00',
                'end' => '2025-05-20 08:25:00',
                'title' => 'Drying hair after shower',
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-20 09:00:00',
                'end' => '2025-05-20 12:30:00',
                'title' => 'Work tasks on laptop',
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-20 15:30:00',
                'end' => '2025-05-20 17:00:00',
                'title' => 'Cooling office during work hours',
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-20 10:30:00',
                'end' => '2025-05-20 10:32:00',
                'title' => 'Mid-morning coffee break',
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-20 12:30:00',
                'end' => '2025-05-20 12:35:00',
                'title' => 'Reheating lunch',
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-20 13:30:00',
                'end' => '2025-05-20 17:30:00',
                'title' => 'Afternoon work session on PC',
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-20 15:00:00',
                'end' => '2025-05-20 15:03:00',
                'title' => 'Boiling water for afternoon tea',
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-20 17:45:00',
                'end' => '2025-05-20 18:05:00',
                'title' => 'Quick evening vacuuming',
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-20 18:30:00',
                'end' => '2025-05-20 19:15:00',
                'title' => 'Cooking dinner',
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-20 19:30:00',
                'end' => '2025-05-20 19:50:00',
                'title' => 'Ironing clothes for tomorrow',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-20 20:30:00',
                'end' => '2025-05-20 22:00:00',
                'title' => 'Evening entertainment - movie',
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-20 22:00:00',
                'end' => '2025-05-20 23:00:00',
                'title' => 'Reading before bed',
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-21 00:00:00',
                'end' => '2025-05-21 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-21 00:00:00',
                'end' => '2025-05-21 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-21 07:00:00',
                'end' => '2025-05-21 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-21 07:05:00',
                'end' => '2025-05-21 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-21 07:30:00',
                'end' => '2025-05-21 08:00:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-21 08:15:00',
                'end' => '2025-05-21 08:20:00',
                'title' => 'Quick hair dry before going to office'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-21 17:30:00',
                'end' => '2025-05-21 18:30:00',
                'title' => 'Running a load of laundry after work'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-21 18:45:00',
                'end' => '2025-05-21 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-21 20:00:00',
                'end' => '2025-05-21 22:00:00',
                'title' => 'Evening entertainment - TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-21 22:00:00',
                'end' => '2025-05-21 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-22 00:00:00',
                'end' => '2025-05-22 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-22 00:00:00',
                'end' => '2025-05-22 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-22 06:50:00',
                'end' => '2025-05-22 06:52:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-22 07:00:00',
                'end' => '2025-05-22 07:05:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-22 07:15:00',
                'end' => '2025-05-22 07:45:00',
                'title' => 'Quick news before heading to work'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-22 07:50:00',
                'end' => '2025-05-22 07:55:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-22 08:00:00',
                'end' => '2025-05-22 09:00:00',
                'title' => 'Heating up the apartment before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-22 17:00:00',
                'end' => '2025-05-22 18:00:00',
                'title' => 'Cooling down the living room upon returning home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-22 18:15:00',
                'end' => '2025-05-22 18:18:00',
                'title' => 'Boiling water for quick soup'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-22 18:30:00',
                'end' => '2025-05-22 20:00:00',
                'title' => 'Evening computer use / Browse'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-22 20:00:00',
                'end' => '2025-05-22 22:30:00',
                'title' => 'Watching a movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-22 22:30:00',
                'end' => '2025-05-22 23:15:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-23 00:00:00',
                'end' => '2025-05-23 23:59:00',
                'title' => 'Fridge all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-23 00:00:00',
                'end' => '2025-05-23 23:59:00',
                'title' => 'Router all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-23 07:15:00',
                'end' => '2025-05-23 07:17:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-23 07:20:00',
                'end' => '2025-05-23 07:25:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-23 08:30:00',
                'end' => '2025-05-23 12:30:00',
                'title' => 'Morning work session on laptop'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-23 11:00:00',
                'end' => '2025-05-23 11:03:00',
                'title' => 'Tea break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-23 12:30:00',
                'end' => '2025-05-23 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-23 13:30:00',
                'end' => '2025-05-23 17:00:00',
                'title' => 'Afternoon work on PC'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-23 14:00:00',
                'end' => '2025-05-23 16:30:00',
                'title' => 'Cooling the house during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-23 17:30:00',
                'end' => '2025-05-23 18:30:00',
                'title' => 'Doing laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-23 18:45:00',
                'end' => '2025-05-23 19:45:00',
                'title' => 'Dinner preparation in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-23 20:00:00',
                'end' => '2025-05-23 22:30:00',
                'title' => 'Watching TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-23 22:30:00',
                'end' => '2025-05-23 23:15:00',
                'title' => 'Reading in bedroom'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-24 00:00:00',
                'end' => '2025-05-24 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-24 00:00:00',
                'end' => '2025-05-24 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-24 08:00:00',
                'end' => '2025-05-24 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-24 09:00:00',
                'end' => '2025-05-24 09:45:00',
                'title' => 'Baking pastries for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-24 10:00:00',
                'end' => '2025-05-24 12:00:00',
                'title' => 'Watching Saturday morning TV'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-24 12:30:00',
                'end' => '2025-05-24 13:00:00',
                'title' => 'Weekend vacuuming'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-24 13:15:00',
                'end' => '2025-05-24 13:20:00',
                'title' => 'Heating up quick lunch'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-24 14:00:00',
                'end' => '2025-05-24 16:00:00',
                'title' => 'Browse and entertainment on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-24 15:00:00',
                'end' => '2025-05-24 17:00:00',
                'title' => 'Cooling during afternoon'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-24 16:30:00',
                'end' => '2025-05-24 16:33:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-24 18:00:00',
                'end' => '2025-05-24 18:30:00',
                'title' => 'Ironing some clothes'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-24 20:30:00',
                'end' => '2025-05-24 23:00:00',
                'title' => 'Saturday night movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-24 23:00:00',
                'end' => '2025-05-24 23:45:00',
                'title' => 'Late night reading'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-25 00:00:00',
                'end' => '2025-05-25 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-25 00:00:00',
                'end' => '2025-05-25 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-25 09:00:00',
                'end' => '2025-05-25 09:03:00',
                'title' => 'Sunday morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-25 10:00:00',
                'end' => '2025-05-25 11:00:00',
                'title' => 'Sunday roast preparation'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-25 11:30:00',
                'end' => '2025-05-25 12:30:00',
                'title' => 'Sunday laundry'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-25 13:00:00',
                'end' => '2025-05-25 14:30:00',
                'title' => 'Sunday afternoon TV'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-25 14:00:00',
                'end' => '2025-05-25 16:00:00',
                'title' => 'Cooling living room'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-25 16:30:00',
                'end' => '2025-05-25 18:00:00',
                'title' => 'Casual computer use'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-25 17:00:00',
                'end' => '2025-05-25 17:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-25 19:00:00',
                'end' => '2025-05-25 19:10:00',
                'title' => 'Heating up leftovers'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-25 20:00:00',
                'end' => '2025-05-25 22:30:00',
                'title' => 'Sunday evening show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-25 22:30:00',
                'end' => '2025-05-25 23:15:00',
                'title' => 'Reading in bed before sleep'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-26 00:00:00',
                'end' => '2025-05-26 23:59:00',
                'title' => 'Fridge constant'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-26 00:00:00',
                'end' => '2025-05-26 23:59:00',
                'title' => 'Router constant'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-26 06:30:00',
                'end' => '2025-05-26 06:32:00',
                'title' => 'Monday morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-26 06:40:00',
                'end' => '2025-05-26 06:45:00',
                'title' => 'Quick breakfast heat-up'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-26 07:00:00',
                'end' => '2025-05-26 07:30:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-26 07:40:00',
                'end' => '2025-05-26 07:45:00',
                'title' => 'Drying hair for work'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-26 08:30:00',
                'end' => '2025-05-26 12:30:00',
                'title' => 'Morning work session'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-26 15:00:00',
                'end' => '2025-05-26 17:00:00',
                'title' => 'AC running during work hours'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-26 10:30:00',
                'end' => '2025-05-26 10:32:00',
                'title' => 'Mid-morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-26 12:30:00',
                'end' => '2025-05-26 12:35:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-26 13:30:00',
                'end' => '2025-05-26 17:30:00',
                'title' => 'Afternoon work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-26 15:00:00',
                'end' => '2025-05-26 15:03:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-26 17:45:00',
                'end' => '2025-05-26 18:45:00',
                'title' => 'Evening laundry load'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-26 19:00:00',
                'end' => '2025-05-26 19:45:00',
                'title' => 'Cooking dinner in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-26 20:30:00',
                'end' => '2025-05-26 22:00:00',
                'title' => 'Evening TV'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-26 22:00:00',
                'end' => '2025-05-26 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-27 00:00:00',
                'end' => '2025-05-27 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-27 00:00:00',
                'end' => '2025-05-27 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-27 07:00:00',
                'end' => '2025-05-27 07:02:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-27 07:05:00',
                'end' => '2025-05-27 07:10:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-27 07:30:00',
                'end' => '2025-05-27 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-27 08:05:00',
                'end' => '2025-05-27 08:10:00',
                'title' => 'Quick dry before going to office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-27 08:30:00',
                'end' => '2025-05-27 09:30:00',
                'title' => 'Heating up apartment before leaving for office'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-27 17:00:00',
                'end' => '2025-05-27 18:00:00',
                'title' => 'Cooling living room after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-27 18:30:00',
                'end' => '2025-05-27 18:33:00',
                'title' => 'Boiling water for instant noodles'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-27 19:00:00',
                'end' => '2025-05-27 19:20:00',
                'title' => 'Ironing clothes'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-27 19:30:00',
                'end' => '2025-05-27 21:00:00',
                'title' => 'Evening computer tasks'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-27 21:00:00',
                'end' => '2025-05-27 22:45:00',
                'title' => 'Watching a documentary'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-27 22:45:00',
                'end' => '2025-05-27 23:30:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-28 00:00:00',
                'end' => '2025-05-28 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-28 00:00:00',
                'end' => '2025-05-28 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-28 06:45:00',
                'end' => '2025-05-28 06:47:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-28 06:50:00',
                'end' => '2025-05-28 06:55:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-28 08:00:00',
                'end' => '2025-05-28 12:00:00',
                'title' => 'Morning work from home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-28 11:00:00',
                'end' => '2025-05-28 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-28 12:00:00',
                'end' => '2025-05-28 12:05:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-28 13:00:00',
                'end' => '2025-05-28 17:00:00',
                'title' => 'Afternoon work from home'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-28 17:30:00',
                'end' => '2025-05-28 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-28 18:45:00',
                'end' => '2025-05-28 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-28 19:45:00',
                'end' => '2025-05-28 20:05:00',
                'title' => 'Quick vacuum after dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-28 20:30:00',
                'end' => '2025-05-28 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-28 22:30:00',
                'end' => '2025-05-28 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-29 00:00:00',
                'end' => '2025-05-29 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-29 00:00:00',
                'end' => '2025-05-29 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-29 07:00:00',
                'end' => '2025-05-29 07:02:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-29 07:05:00',
                'end' => '2025-05-29 07:10:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-29 07:30:00',
                'end' => '2025-05-29 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-29 08:15:00',
                'end' => '2025-05-29 08:20:00',
                'title' => 'Drying hair before office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-29 08:30:00',
                'end' => '2025-05-29 09:30:00',
                'title' => 'Heating the house before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-29 17:00:00',
                'end' => '2025-05-29 18:00:00',
                'title' => 'Cooling the house after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-29 18:30:00',
                'end' => '2025-05-29 18:33:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-29 19:00:00',
                'end' => '2025-05-29 19:45:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-29 20:00:00',
                'end' => '2025-05-29 20:20:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-29 20:30:00',
                'end' => '2025-05-29 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-29 22:30:00',
                'end' => '2025-05-29 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-19 00:00:00',
                'end' => '2025-05-19 23:59:00',
                'title' => 'Fridge on all day',
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-19 00:00:00',
                'end' => '2025-05-19 23:59:00',
                'title' => 'Wi-Fi router active all day',
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-19 06:45:00',
                'end' => '2025-05-19 06:47:00',
                'title' => 'Early morning coffee',
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-19 07:00:00',
                'end' => '2025-05-19 07:05:00',
                'title' => 'Warming up oatmeal for breakfast',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-19 07:20:00',
                'end' => '2025-05-19 08:00:00',
                'title' => 'Catching up on morning news',
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-19 08:05:00',
                'end' => '2025-05-19 08:10:00',
                'title' => 'Quick hair dry before work',
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-19 08:50:00',
                'end' => '2025-05-19 12:30:00',
                'title' => 'Work on PC - morning session',
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-19 10:00:00',
                'end' => '2025-05-19 11:00:00',
                'title' => 'Morning cooling burst during work',
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-19 11:00:00',
                'end' => '2025-05-19 11:03:00',
                'title' => 'Boiling water for tea',
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-19 12:35:00',
                'end' => '2025-05-19 12:40:00',
                'title' => 'Lunch reheating',
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-19 13:30:00',
                'end' => '2025-05-19 17:15:00',
                'title' => 'Afternoon tasks and meetings on laptop',
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-19 14:30:00',
                'end' => '2025-05-19 15:30:00',
                'title' => 'Afternoon cooling burst during work',
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-19 17:30:00',
                'end' => '2025-05-19 18:30:00',
                'title' => 'Running a load of laundry',
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-19 18:45:00',
                'end' => '2025-05-19 19:30:00',
                'title' => 'Baking dinner',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-19 20:15:00',
                'end' => '2025-05-19 22:00:00',
                'title' => 'Watching a TV series',
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-19 22:00:00',
                'end' => '2025-05-19 22:45:00',
                'title' => 'Evening reading with lamp',
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-18 00:00:00',
                'end' => '2025-05-18 23:59:00',
                'title' => 'Fridge on all day',
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-18 00:00:00',
                'end' => '2025-05-18 23:59:00',
                'title' => 'Internet router always on',
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-18 08:30:00',
                'end' => '2025-05-18 08:33:00',
                'title' => 'Weekend morning coffee',
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-18 09:00:00',
                'end' => '2025-05-18 09:45:00',
                'title' => 'Making a big Sunday breakfast/brunch',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-18 10:00:00',
                'end' => '2025-05-18 11:30:00',
                'title' => 'Watching cartoons with kids / morning show',
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-18 11:45:00',
                'end' => '2025-05-18 12:15:00',
                'title' => 'Sunday house cleaning - vacuuming',
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-18 13:00:00',
                'end' => '2025-05-18 14:30:00',
                'title' => 'Browse internet and personal tasks',
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-18 14:00:00',
                'end' => '2025-05-18 14:45:00',
                'title' => 'Cooling down living room - part 1',
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-18 15:00:00',
                'end' => '2025-05-18 16:00:00',
                'title' => 'Watching videos on laptop',
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-18 15:45:00',
                'end' => '2025-05-18 16:30:00',
                'title' => 'Cooling down living room - part 2',
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-18 16:30:00',
                'end' => '2025-05-18 16:33:00',
                'title' => 'Afternoon tea time',
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-18 18:00:00',
                'end' => '2025-05-18 18:10:00',
                'title' => 'Heating up leftovers for a quick dinner',
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-18 19:00:00',
                'end' => '2025-05-18 19:30:00',
                'title' => 'Preparing clothes for the week ahead',
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-18 20:00:00',
                'end' => '2025-05-18 22:15:00',
                'title' => 'Sunday evening movie',
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-18 22:15:00',
                'end' => '2025-05-18 23:00:00',
                'title' => 'Reading in bed',
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-17 00:00:00',
                'end' => '2025-05-17 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-17 00:00:00',
                'end' => '2025-05-17 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-17 08:00:00',
                'end' => '2025-05-17 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-17 08:15:00',
                'end' => '2025-05-17 08:20:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-17 09:00:00',
                'end' => '2025-05-17 10:30:00',
                'title' => 'Watching Saturday morning show'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-17 11:00:00',
                'end' => '2025-05-17 12:00:00',
                'title' => 'Saturday laundry'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-17 12:15:00',
                'end' => '2025-05-17 12:45:00',
                'title' => 'Weekend house cleaning'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-17 14:00:00',
                'end' => '2025-05-17 16:00:00',
                'title' => 'Casual computer Browse'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-17 15:00:00',
                'end' => '2025-05-17 17:00:00',
                'title' => 'Cooling down living room'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-17 17:30:00',
                'end' => '2025-05-17 17:33:00',
                'title' => 'Afternoon tea time'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-17 18:30:00',
                'end' => '2025-05-17 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-17 19:45:00',
                'end' => '2025-05-17 20:15:00',
                'title' => 'Ironing clothes for evening'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-17 20:30:00',
                'end' => '2025-05-17 23:00:00',
                'title' => 'Saturday evening movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-17 23:00:00',
                'end' => '2025-05-17 23:45:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-16 00:00:00',
                'end' => '2025-05-16 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-16 00:00:00',
                'end' => '2025-05-16 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-16 06:45:00',
                'end' => '2025-05-16 06:47:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-16 07:00:00',
                'end' => '2025-05-16 07:05:00',
                'title' => 'Warming up oatmeal for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-16 07:20:00',
                'end' => '2025-05-16 08:00:00',
                'title' => 'Catching up on morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-16 08:05:00',
                'end' => '2025-05-16 08:10:00',
                'title' => 'Quick hair dry before work'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-16 08:50:00',
                'end' => '2025-05-16 12:30:00',
                'title' => 'Work on PC - morning session'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-16 10:00:00',
                'end' => '2025-05-16 11:00:00',
                'title' => 'Morning cooling burst during work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-16 11:00:00',
                'end' => '2025-05-16 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-16 12:35:00',
                'end' => '2025-05-16 12:40:00',
                'title' => 'Lunch reheating'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-16 13:30:00',
                'end' => '2025-05-16 17:15:00',
                'title' => 'Afternoon tasks and meetings on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-16 14:30:00',
                'end' => '2025-05-16 15:30:00',
                'title' => 'Afternoon cooling burst during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-16 17:30:00',
                'end' => '2025-05-16 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-16 18:45:00',
                'end' => '2025-05-16 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-16 20:15:00',
                'end' => '2025-05-16 22:00:00',
                'title' => 'Watching a TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-16 22:00:00',
                'end' => '2025-05-16 22:45:00',
                'title' => 'Evening reading with lamp'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-15 00:00:00',
                'end' => '2025-05-15 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-15 00:00:00',
                'end' => '2025-05-15 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-15 07:00:00',
                'end' => '2025-05-15 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-15 07:05:00',
                'end' => '2025-05-15 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-15 07:30:00',
                'end' => '2025-05-15 08:15:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-15 08:20:00',
                'end' => '2025-05-15 08:25:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-15 09:00:00',
                'end' => '2025-05-15 12:30:00',
                'title' => 'Work tasks on laptop'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-15 10:30:00',
                'end' => '2025-05-15 10:32:00',
                'title' => 'Mid-morning coffee break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-15 12:30:00',
                'end' => '2025-05-15 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-15 13:30:00',
                'end' => '2025-05-15 17:30:00',
                'title' => 'Afternoon work session on PC'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-15 15:00:00',
                'end' => '2025-05-15 15:03:00',
                'title' => 'Boiling water for afternoon tea'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-15 17:45:00',
                'end' => '2025-05-15 18:05:00',
                'title' => 'Quick evening vacuuming'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-15 18:30:00',
                'end' => '2025-05-15 19:15:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-15 19:30:00',
                'end' => '2025-05-15 19:50:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-15 20:30:00',
                'end' => '2025-05-15 22:00:00',
                'title' => 'Evening entertainment - movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-15 22:00:00',
                'end' => '2025-05-15 23:00:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-14 00:00:00',
                'end' => '2025-05-14 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-14 00:00:00',
                'end' => '2025-05-14 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-14 07:00:00',
                'end' => '2025-05-14 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-14 07:05:00',
                'end' => '2025-05-14 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-14 07:30:00',
                'end' => '2025-05-14 08:00:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-14 08:15:00',
                'end' => '2025-05-14 08:20:00',
                'title' => 'Quick hair dry before going to office'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-14 17:30:00',
                'end' => '2025-05-14 18:30:00',
                'title' => 'Running a load of laundry after work'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-14 18:45:00',
                'end' => '2025-05-14 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-14 20:00:00',
                'end' => '2025-05-14 22:00:00',
                'title' => 'Evening entertainment - TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-14 22:00:00',
                'end' => '2025-05-14 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-13 00:00:00',
                'end' => '2025-05-13 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-13 00:00:00',
                'end' => '2025-05-13 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-13 06:50:00',
                'end' => '2025-05-13 06:52:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-13 07:00:00',
                'end' => '2025-05-13 07:05:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-13 07:15:00',
                'end' => '2025-05-13 07:45:00',
                'title' => 'Quick news before heading to work'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-13 07:50:00',
                'end' => '2025-05-13 07:55:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-13 08:00:00',
                'end' => '2025-05-13 09:00:00',
                'title' => 'Heating up the apartment before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-13 17:00:00',
                'end' => '2025-05-13 18:00:00',
                'title' => 'Cooling down the living room upon returning home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-13 18:15:00',
                'end' => '2025-05-13 18:18:00',
                'title' => 'Boiling water for quick soup'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-13 18:30:00',
                'end' => '2025-05-13 20:00:00',
                'title' => 'Evening computer use / Browse'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-13 20:00:00',
                'end' => '2025-05-13 22:30:00',
                'title' => 'Watching a movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-13 22:30:00',
                'end' => '2025-05-13 23:15:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-12 00:00:00',
                'end' => '2025-05-12 23:59:00',
                'title' => 'Fridge all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-12 00:00:00',
                'end' => '2025-05-12 23:59:00',
                'title' => 'Router all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-12 07:15:00',
                'end' => '2025-05-12 07:17:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-12 07:20:00',
                'end' => '2025-05-12 07:25:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-12 08:30:00',
                'end' => '2025-05-12 12:30:00',
                'title' => 'Morning work session on laptop'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-12 11:00:00',
                'end' => '2025-05-12 11:03:00',
                'title' => 'Tea break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-12 12:30:00',
                'end' => '2025-05-12 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-12 13:30:00',
                'end' => '2025-05-12 17:00:00',
                'title' => 'Afternoon work on PC'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-12 14:00:00',
                'end' => '2025-05-12 16:30:00',
                'title' => 'Cooling the house during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-12 17:30:00',
                'end' => '2025-05-12 18:30:00',
                'title' => 'Doing laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-12 18:45:00',
                'end' => '2025-05-12 19:45:00',
                'title' => 'Dinner preparation in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-12 20:00:00',
                'end' => '2025-05-12 22:30:00',
                'title' => 'Watching TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-12 22:30:00',
                'end' => '2025-05-12 23:15:00',
                'title' => 'Reading in bedroom'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-11 00:00:00',
                'end' => '2025-05-11 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-11 00:00:00',
                'end' => '2025-05-11 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-11 08:00:00',
                'end' => '2025-05-11 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-11 09:00:00',
                'end' => '2025-05-11 09:45:00',
                'title' => 'Baking pastries for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-11 10:00:00',
                'end' => '2025-05-11 12:00:00',
                'title' => 'Watching Sunday morning TV'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-11 12:30:00',
                'end' => '2025-05-11 13:00:00',
                'title' => 'Weekend vacuuming'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-11 13:15:00',
                'end' => '2025-05-11 13:20:00',
                'title' => 'Heating up quick lunch'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-11 14:00:00',
                'end' => '2025-05-11 16:00:00',
                'title' => 'Browse and entertainment on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-11 15:00:00',
                'end' => '2025-05-11 17:00:00',
                'title' => 'Cooling during afternoon'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-11 16:30:00',
                'end' => '2025-05-11 16:33:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-11 18:00:00',
                'end' => '2025-05-11 18:30:00',
                'title' => 'Ironing some clothes'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-11 20:30:00',
                'end' => '2025-05-11 23:00:00',
                'title' => 'Sunday night movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-11 23:00:00',
                'end' => '2025-05-11 23:45:00',
                'title' => 'Late night reading'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-10 00:00:00',
                'end' => '2025-05-10 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-10 00:00:00',
                'end' => '2025-05-10 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-10 09:00:00',
                'end' => '2025-05-10 09:03:00',
                'title' => 'Saturday morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-10 10:00:00',
                'end' => '2025-05-10 11:00:00',
                'title' => 'Saturday baking session'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-10 11:30:00',
                'end' => '2025-05-10 12:30:00',
                'title' => 'Saturday laundry'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-10 13:00:00',
                'end' => '2025-05-10 14:30:00',
                'title' => 'Saturday afternoon TV'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-10 14:00:00',
                'end' => '2025-05-10 16:00:00',
                'title' => 'Cooling living room'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-10 16:30:00',
                'end' => '2025-05-10 18:00:00',
                'title' => 'Casual computer use'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-10 17:00:00',
                'end' => '2025-05-10 17:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-10 19:00:00',
                'end' => '2025-05-10 19:10:00',
                'title' => 'Heating up quick dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-10 20:00:00',
                'end' => '2025-05-10 22:30:00',
                'title' => 'Saturday evening entertainment'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-10 22:30:00',
                'end' => '2025-05-10 23:15:00',
                'title' => 'Reading in bed before sleep'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-09 00:00:00',
                'end' => '2025-05-09 23:59:00',
                'title' => 'Fridge constant'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-09 00:00:00',
                'end' => '2025-05-09 23:59:00',
                'title' => 'Router constant'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-09 06:30:00',
                'end' => '2025-05-09 06:32:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-09 06:40:00',
                'end' => '2025-05-09 06:45:00',
                'title' => 'Quick breakfast heat-up'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-09 07:00:00',
                'end' => '2025-05-09 07:30:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-09 07:40:00',
                'end' => '2025-05-09 07:45:00',
                'title' => 'Drying hair for work'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-09 08:30:00',
                'end' => '2025-05-09 12:30:00',
                'title' => 'Morning work session'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-09 15:00:00',
                'end' => '2025-05-09 17:00:00',
                'title' => 'AC running during work hours'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-09 10:30:00',
                'end' => '2025-05-09 10:32:00',
                'title' => 'Mid-morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-09 12:30:00',
                'end' => '2025-05-09 12:35:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-09 13:30:00',
                'end' => '2025-05-09 17:30:00',
                'title' => 'Afternoon work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-09 15:00:00',
                'end' => '2025-05-09 15:03:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-09 17:45:00',
                'end' => '2025-05-09 18:45:00',
                'title' => 'Evening laundry load'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-09 19:00:00',
                'end' => '2025-05-09 19:45:00',
                'title' => 'Cooking dinner in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-09 20:30:00',
                'end' => '2025-05-09 22:00:00',
                'title' => 'Evening TV'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-09 22:00:00',
                'end' => '2025-05-09 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-08 00:00:00',
                'end' => '2025-05-08 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-08 00:00:00',
                'end' => '2025-05-08 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-08 07:00:00',
                'end' => '2025-05-08 07:02:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-08 07:05:00',
                'end' => '2025-05-08 07:10:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-08 07:30:00',
                'end' => '2025-05-08 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-08 08:05:00',
                'end' => '2025-05-08 08:10:00',
                'title' => 'Quick dry before going to office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-08 08:30:00',
                'end' => '2025-05-08 09:30:00',
                'title' => 'Heating up apartment before leaving for office'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-08 17:00:00',
                'end' => '2025-05-08 18:00:00',
                'title' => 'Cooling living room after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-08 18:30:00',
                'end' => '2025-05-08 18:33:00',
                'title' => 'Boiling water for instant noodles'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-08 19:00:00',
                'end' => '2025-05-08 19:20:00',
                'title' => 'Ironing clothes'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-08 19:30:00',
                'end' => '2025-05-08 21:00:00',
                'title' => 'Evening computer tasks'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-08 21:00:00',
                'end' => '2025-05-08 22:45:00',
                'title' => 'Watching a documentary'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-08 22:45:00',
                'end' => '2025-05-08 23:30:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-07 00:00:00',
                'end' => '2025-05-07 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-07 00:00:00',
                'end' => '2025-05-07 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-07 06:45:00',
                'end' => '2025-05-07 06:47:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-07 06:50:00',
                'end' => '2025-05-07 06:55:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-07 08:00:00',
                'end' => '2025-05-07 12:00:00',
                'title' => 'Morning work from home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-07 11:00:00',
                'end' => '2025-05-07 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-07 12:00:00',
                'end' => '2025-05-07 12:05:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-07 13:00:00',
                'end' => '2025-05-07 17:00:00',
                'title' => 'Afternoon work from home'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-07 17:30:00',
                'end' => '2025-05-07 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-07 18:45:00',
                'end' => '2025-05-07 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-07 19:45:00',
                'end' => '2025-05-07 20:05:00',
                'title' => 'Quick vacuum after dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-07 20:30:00',
                'end' => '2025-05-07 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-07 22:30:00',
                'end' => '2025-05-07 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-06 00:00:00',
                'end' => '2025-05-06 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-06 00:00:00',
                'end' => '2025-05-06 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-06 07:00:00',
                'end' => '2025-05-06 07:02:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-06 07:05:00',
                'end' => '2025-05-06 07:10:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-06 07:30:00',
                'end' => '2025-05-06 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-06 08:15:00',
                'end' => '2025-05-06 08:20:00',
                'title' => 'Drying hair before office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-05-06 08:30:00',
                'end' => '2025-05-06 09:30:00',
                'title' => 'Heating the house before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-06 17:00:00',
                'end' => '2025-05-06 18:00:00',
                'title' => 'Cooling the house after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-06 18:30:00',
                'end' => '2025-05-06 18:33:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-06 19:00:00',
                'end' => '2025-05-06 19:45:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-06 20:00:00',
                'end' => '2025-05-06 20:20:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-06 20:30:00',
                'end' => '2025-05-06 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-06 22:30:00',
                'end' => '2025-05-06 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-05 00:00:00',
                'end' => '2025-05-05 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-05 00:00:00',
                'end' => '2025-05-05 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-05 07:00:00',
                'end' => '2025-05-05 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-05 07:05:00',
                'end' => '2025-05-05 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-05 07:30:00',
                'end' => '2025-05-05 08:15:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-05 08:20:00',
                'end' => '2025-05-05 08:25:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-05 09:00:00',
                'end' => '2025-05-05 12:30:00',
                'title' => 'Work tasks on laptop'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-05 10:30:00',
                'end' => '2025-05-05 10:32:00',
                'title' => 'Mid-morning coffee break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-05 12:30:00',
                'end' => '2025-05-05 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-05 13:30:00',
                'end' => '2025-05-05 17:30:00',
                'title' => 'Afternoon work session on PC'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-05 15:00:00',
                'end' => '2025-05-05 15:03:00',
                'title' => 'Boiling water for afternoon tea'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-05 17:45:00',
                'end' => '2025-05-05 18:05:00',
                'title' => 'Quick evening vacuuming'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-05 18:30:00',
                'end' => '2025-05-05 19:15:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-05 19:30:00',
                'end' => '2025-05-05 19:50:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-05 20:30:00',
                'end' => '2025-05-05 22:00:00',
                'title' => 'Evening entertainment - movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-05 22:00:00',
                'end' => '2025-05-05 23:00:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-04 00:00:00',
                'end' => '2025-05-04 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-04 00:00:00',
                'end' => '2025-05-04 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-04 08:30:00',
                'end' => '2025-05-04 08:33:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-04 09:00:00',
                'end' => '2025-05-04 09:45:00',
                'title' => 'Making a big Sunday breakfast/brunch'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-04 10:00:00',
                'end' => '2025-05-04 11:30:00',
                'title' => 'Watching cartoons with kids / morning show'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-04 11:45:00',
                'end' => '2025-05-04 12:15:00',
                'title' => 'Sunday house cleaning - vacuuming'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-04 13:00:00',
                'end' => '2025-05-04 14:30:00',
                'title' => 'Browse internet and personal tasks'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-04 14:00:00',
                'end' => '2025-05-04 14:45:00',
                'title' => 'Cooling down living room - part 1'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-04 15:00:00',
                'end' => '2025-05-04 16:00:00',
                'title' => 'Watching videos on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-04 15:45:00',
                'end' => '2025-05-04 16:30:00',
                'title' => 'Cooling down living room - part 2'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-04 16:30:00',
                'end' => '2025-05-04 16:33:00',
                'title' => 'Afternoon tea time'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-04 18:00:00',
                'end' => '2025-05-04 18:10:00',
                'title' => 'Heating up leftovers for a quick dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-04 19:00:00',
                'end' => '2025-05-04 19:30:00',
                'title' => 'Preparing clothes for the week ahead'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-04 20:00:00',
                'end' => '2025-05-04 22:15:00',
                'title' => 'Sunday evening movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-04 22:15:00',
                'end' => '2025-05-04 23:00:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-03 00:00:00',
                'end' => '2025-05-03 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-03 00:00:00',
                'end' => '2025-05-03 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-03 08:00:00',
                'end' => '2025-05-03 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-03 08:15:00',
                'end' => '2025-05-03 08:20:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-03 09:00:00',
                'end' => '2025-05-03 10:30:00',
                'title' => 'Watching Saturday morning show'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-03 11:00:00',
                'end' => '2025-05-03 12:00:00',
                'title' => 'Saturday laundry'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-03 12:15:00',
                'end' => '2025-05-03 12:45:00',
                'title' => 'Weekend house cleaning'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-03 14:00:00',
                'end' => '2025-05-03 16:00:00',
                'title' => 'Casual computer Browse'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-03 15:00:00',
                'end' => '2025-05-03 17:00:00',
                'title' => 'Cooling down living room'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-03 17:30:00',
                'end' => '2025-05-03 17:33:00',
                'title' => 'Afternoon tea time'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-03 18:30:00',
                'end' => '2025-05-03 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-03 19:45:00',
                'end' => '2025-05-03 20:15:00',
                'title' => 'Ironing clothes for evening'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-03 20:30:00',
                'end' => '2025-05-03 23:00:00',
                'title' => 'Saturday evening movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-03 23:00:00',
                'end' => '2025-05-03 23:45:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-02 00:00:00',
                'end' => '2025-05-02 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-02 00:00:00',
                'end' => '2025-05-02 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-02 06:45:00',
                'end' => '2025-05-02 06:47:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-02 07:00:00',
                'end' => '2025-05-02 07:05:00',
                'title' => 'Warming up oatmeal for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-02 07:20:00',
                'end' => '2025-05-02 08:00:00',
                'title' => 'Catching up on morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-02 08:05:00',
                'end' => '2025-05-02 08:10:00',
                'title' => 'Quick hair dry before work'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-02 08:50:00',
                'end' => '2025-05-02 12:30:00',
                'title' => 'Work on PC - morning session'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-02 10:00:00',
                'end' => '2025-05-02 11:00:00',
                'title' => 'Morning cooling burst during work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-02 11:00:00',
                'end' => '2025-05-02 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-02 12:35:00',
                'end' => '2025-05-02 12:40:00',
                'title' => 'Lunch reheating'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-02 13:30:00',
                'end' => '2025-05-02 17:15:00',
                'title' => 'Afternoon tasks and meetings on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-05-02 14:30:00',
                'end' => '2025-05-02 15:30:00',
                'title' => 'Afternoon cooling burst during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-05-02 17:30:00',
                'end' => '2025-05-02 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-02 18:45:00',
                'end' => '2025-05-02 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-02 20:15:00',
                'end' => '2025-05-02 22:00:00',
                'title' => 'Watching a TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-02 22:00:00',
                'end' => '2025-05-02 22:45:00',
                'title' => 'Evening reading with lamp'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-05-01 00:00:00',
                'end' => '2025-05-01 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-05-01 00:00:00',
                'end' => '2025-05-01 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-01 07:00:00',
                'end' => '2025-05-01 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-01 07:05:00',
                'end' => '2025-05-01 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-01 07:30:00',
                'end' => '2025-05-01 08:15:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-05-01 08:20:00',
                'end' => '2025-05-01 08:25:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-05-01 09:00:00',
                'end' => '2025-05-01 12:30:00',
                'title' => 'Work tasks on laptop'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-05-01 10:30:00',
                'end' => '2025-05-01 10:32:00',
                'title' => 'Mid-morning coffee break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-05-01 12:30:00',
                'end' => '2025-05-01 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-05-01 13:30:00',
                'end' => '2025-05-01 17:30:00',
                'title' => 'Afternoon work session on PC'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-05-01 15:00:00',
                'end' => '2025-05-01 15:03:00',
                'title' => 'Boiling water for afternoon tea'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-05-01 17:45:00',
                'end' => '2025-05-01 18:05:00',
                'title' => 'Quick evening vacuuming'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-05-01 18:30:00',
                'end' => '2025-05-01 19:15:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-05-01 19:30:00',
                'end' => '2025-05-01 19:50:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-05-01 20:30:00',
                'end' => '2025-05-01 22:00:00',
                'title' => 'Evening entertainment - movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-05-01 22:00:00',
                'end' => '2025-05-01 23:00:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-30 00:00:00',
                'end' => '2025-04-30 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-30 00:00:00',
                'end' => '2025-04-30 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-30 07:00:00',
                'end' => '2025-04-30 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-30 07:05:00',
                'end' => '2025-04-30 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-30 07:30:00',
                'end' => '2025-04-30 08:00:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-30 08:15:00',
                'end' => '2025-04-30 08:20:00',
                'title' => 'Quick hair dry before going to office'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-30 17:30:00',
                'end' => '2025-04-30 18:30:00',
                'title' => 'Running a load of laundry after work'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-30 18:45:00',
                'end' => '2025-04-30 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-30 20:00:00',
                'end' => '2025-04-30 22:00:00',
                'title' => 'Evening entertainment - TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-30 22:00:00',
                'end' => '2025-04-30 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-29 00:00:00',
                'end' => '2025-04-29 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-29 00:00:00',
                'end' => '2025-04-29 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-29 06:50:00',
                'end' => '2025-04-29 06:52:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-29 07:00:00',
                'end' => '2025-04-29 07:05:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-29 07:15:00',
                'end' => '2025-04-29 07:45:00',
                'title' => 'Quick news before heading to work'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-29 07:50:00',
                'end' => '2025-04-29 07:55:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-04-29 08:00:00',
                'end' => '2025-04-29 09:00:00',
                'title' => 'Heating up the apartment before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-29 17:00:00',
                'end' => '2025-04-29 18:00:00',
                'title' => 'Cooling down the living room upon returning home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-29 18:15:00',
                'end' => '2025-04-29 18:18:00',
                'title' => 'Boiling water for quick soup'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-29 18:30:00',
                'end' => '2025-04-29 20:00:00',
                'title' => 'Evening computer use / Browse'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-29 20:00:00',
                'end' => '2025-04-29 22:30:00',
                'title' => 'Watching a movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-29 22:30:00',
                'end' => '2025-04-29 23:15:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-28 00:00:00',
                'end' => '2025-04-28 23:59:00',
                'title' => 'Fridge all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-28 00:00:00',
                'end' => '2025-04-28 23:59:00',
                'title' => 'Router all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-28 07:15:00',
                'end' => '2025-04-28 07:17:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-28 07:20:00',
                'end' => '2025-04-28 07:25:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-28 08:30:00',
                'end' => '2025-04-28 12:30:00',
                'title' => 'Morning work session on laptop'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-28 11:00:00',
                'end' => '2025-04-28 11:03:00',
                'title' => 'Tea break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-28 12:30:00',
                'end' => '2025-04-28 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-28 13:30:00',
                'end' => '2025-04-28 17:00:00',
                'title' => 'Afternoon work on PC'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-28 14:00:00',
                'end' => '2025-04-28 16:30:00',
                'title' => 'Cooling the house during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-28 17:30:00',
                'end' => '2025-04-28 18:30:00',
                'title' => 'Doing laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-28 18:45:00',
                'end' => '2025-04-28 19:45:00',
                'title' => 'Dinner preparation in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-28 20:00:00',
                'end' => '2025-04-28 22:30:00',
                'title' => 'Watching TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-28 22:30:00',
                'end' => '2025-04-28 23:15:00',
                'title' => 'Reading in bedroom'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-27 00:00:00',
                'end' => '2025-04-27 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-27 00:00:00',
                'end' => '2025-04-27 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-27 08:00:00',
                'end' => '2025-04-27 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-27 09:00:00',
                'end' => '2025-04-27 09:45:00',
                'title' => 'Baking pastries for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-27 10:00:00',
                'end' => '2025-04-27 12:00:00',
                'title' => 'Watching Sunday morning TV'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-04-27 12:30:00',
                'end' => '2025-04-27 13:00:00',
                'title' => 'Weekend vacuuming'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-27 13:15:00',
                'end' => '2025-04-27 13:20:00',
                'title' => 'Heating up quick lunch'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-27 14:00:00',
                'end' => '2025-04-27 16:00:00',
                'title' => 'Browse and entertainment on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-27 15:00:00',
                'end' => '2025-04-27 17:00:00',
                'title' => 'Cooling during afternoon'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-27 16:30:00',
                'end' => '2025-04-27 16:33:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-27 18:00:00',
                'end' => '2025-04-27 18:30:00',
                'title' => 'Ironing some clothes'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-27 20:30:00',
                'end' => '2025-04-27 23:00:00',
                'title' => 'Sunday night movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-27 23:00:00',
                'end' => '2025-04-27 23:45:00',
                'title' => 'Late night reading'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-26 00:00:00',
                'end' => '2025-04-26 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-26 00:00:00',
                'end' => '2025-04-26 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-26 09:00:00',
                'end' => '2025-04-26 09:03:00',
                'title' => 'Saturday morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-26 10:00:00',
                'end' => '2025-04-26 11:00:00',
                'title' => 'Saturday baking session'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-26 11:30:00',
                'end' => '2025-04-26 12:30:00',
                'title' => 'Saturday laundry'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-26 13:00:00',
                'end' => '2025-04-26 14:30:00',
                'title' => 'Saturday afternoon TV'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-26 14:00:00',
                'end' => '2025-04-26 16:00:00',
                'title' => 'Cooling living room'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-26 16:30:00',
                'end' => '2025-04-26 18:00:00',
                'title' => 'Casual computer use'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-26 17:00:00',
                'end' => '2025-04-26 17:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-26 19:00:00',
                'end' => '2025-04-26 19:10:00',
                'title' => 'Heating up quick dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-26 20:00:00',
                'end' => '2025-04-26 22:30:00',
                'title' => 'Saturday evening entertainment'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-26 22:30:00',
                'end' => '2025-04-26 23:15:00',
                'title' => 'Reading in bed before sleep'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-25 00:00:00',
                'end' => '2025-04-25 23:59:00',
                'title' => 'Fridge constant'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-25 00:00:00',
                'end' => '2025-04-25 23:59:00',
                'title' => 'Router constant'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-25 06:30:00',
                'end' => '2025-04-25 06:32:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-25 06:40:00',
                'end' => '2025-04-25 06:45:00',
                'title' => 'Quick breakfast heat-up'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-25 07:00:00',
                'end' => '2025-04-25 07:30:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-25 07:40:00',
                'end' => '2025-04-25 07:45:00',
                'title' => 'Drying hair for work'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-25 08:30:00',
                'end' => '2025-04-25 12:30:00',
                'title' => 'Morning work session'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-25 10:30:00',
                'end' => '2025-04-25 10:32:00',
                'title' => 'Mid-morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-25 12:30:00',
                'end' => '2025-04-25 12:35:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-25 13:30:00',
                'end' => '2025-04-25 17:30:00',
                'title' => 'Afternoon work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-25 15:00:00',
                'end' => '2025-04-25 15:03:00',
                'title' => 'Afternoon tea'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-25 17:45:00',
                'end' => '2025-04-25 18:45:00',
                'title' => 'Evening laundry load'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-25 19:00:00',
                'end' => '2025-04-25 19:45:00',
                'title' => 'Cooking dinner in oven'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-25 20:30:00',
                'end' => '2025-04-25 22:00:00',
                'title' => 'Evening TV'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-25 22:00:00',
                'end' => '2025-04-25 22:45:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-24 00:00:00',
                'end' => '2025-04-24 23:59:00',
                'title' => 'Fridge on'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-24 00:00:00',
                'end' => '2025-04-24 23:59:00',
                'title' => 'Router on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-24 07:00:00',
                'end' => '2025-04-24 07:02:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-24 07:05:00',
                'end' => '2025-04-24 07:10:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-24 07:30:00',
                'end' => '2025-04-24 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-24 08:05:00',
                'end' => '2025-04-24 08:10:00',
                'title' => 'Quick dry before going to office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-04-24 08:30:00',
                'end' => '2025-04-24 09:30:00',
                'title' => 'Heating up apartment before leaving for office'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-24 17:00:00',
                'end' => '2025-04-24 18:00:00',
                'title' => 'Cooling living room after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-24 18:30:00',
                'end' => '2025-04-24 18:33:00',
                'title' => 'Boiling water for instant noodles'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-24 19:00:00',
                'end' => '2025-04-24 19:20:00',
                'title' => 'Ironing clothes'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-24 19:30:00',
                'end' => '2025-04-24 21:00:00',
                'title' => 'Evening computer tasks'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-24 21:00:00',
                'end' => '2025-04-24 22:45:00',
                'title' => 'Watching a documentary'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-24 22:45:00',
                'end' => '2025-04-24 23:30:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-23 00:00:00',
                'end' => '2025-04-23 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-23 00:00:00',
                'end' => '2025-04-23 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-23 06:45:00',
                'end' => '2025-04-23 06:47:00',
                'title' => 'Morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-23 06:50:00',
                'end' => '2025-04-23 06:55:00',
                'title' => 'Heating breakfast'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-23 08:00:00',
                'end' => '2025-04-23 12:00:00',
                'title' => 'Morning work from home'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-23 11:00:00',
                'end' => '2025-04-23 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-23 12:00:00',
                'end' => '2025-04-23 12:05:00',
                'title' => 'Lunch reheat'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-23 13:00:00',
                'end' => '2025-04-23 17:00:00',
                'title' => 'Afternoon work from home'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-23 17:30:00',
                'end' => '2025-04-23 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-23 18:45:00',
                'end' => '2025-04-23 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-04-23 19:45:00',
                'end' => '2025-04-23 20:05:00',
                'title' => 'Quick vacuum after dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-23 20:30:00',
                'end' => '2025-04-23 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-23 22:30:00',
                'end' => '2025-04-23 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-22 00:00:00',
                'end' => '2025-04-22 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-22 00:00:00',
                'end' => '2025-04-22 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-22 07:00:00',
                'end' => '2025-04-22 07:02:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-22 07:05:00',
                'end' => '2025-04-22 07:10:00',
                'title' => 'Warming up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-22 07:30:00',
                'end' => '2025-04-22 08:00:00',
                'title' => 'Morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-22 08:15:00',
                'end' => '2025-04-22 08:20:00',
                'title' => 'Drying hair before office'
            ],
            [
                'device_name' => 'Heater',
                'start' => '2025-04-22 08:30:00',
                'end' => '2025-04-22 09:30:00',
                'title' => 'Heating the house before leaving'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-22 17:00:00',
                'end' => '2025-04-22 18:00:00',
                'title' => 'Cooling the house after work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-22 18:30:00',
                'end' => '2025-04-22 18:33:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-22 19:00:00',
                'end' => '2025-04-22 19:45:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-22 20:00:00',
                'end' => '2025-04-22 20:20:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-22 20:30:00',
                'end' => '2025-04-22 22:30:00',
                'title' => 'Evening TV show'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-22 22:30:00',
                'end' => '2025-04-22 23:15:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-21 00:00:00',
                'end' => '2025-04-21 23:59:00',
                'title' => 'Fridge runs all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-21 00:00:00',
                'end' => '2025-04-21 23:59:00',
                'title' => 'Wifi router runs all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-21 07:00:00',
                'end' => '2025-04-21 07:02:00',
                'title' => 'Morning coffee ritual'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-21 07:05:00',
                'end' => '2025-04-21 07:10:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-21 07:30:00',
                'end' => '2025-04-21 08:15:00',
                'title' => 'Morning news and weather'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-21 08:20:00',
                'end' => '2025-04-21 08:25:00',
                'title' => 'Drying hair after shower'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-21 09:00:00',
                'end' => '2025-04-21 12:30:00',
                'title' => 'Work tasks on laptop'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-21 10:30:00',
                'end' => '2025-04-21 10:32:00',
                'title' => 'Mid-morning coffee break'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-21 12:30:00',
                'end' => '2025-04-21 12:35:00',
                'title' => 'Reheating lunch'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-21 13:30:00',
                'end' => '2025-04-21 17:30:00',
                'title' => 'Afternoon work session on PC'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-21 15:00:00',
                'end' => '2025-04-21 15:03:00',
                'title' => 'Boiling water for afternoon tea'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-04-21 17:45:00',
                'end' => '2025-04-21 18:05:00',
                'title' => 'Quick evening vacuuming'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-21 18:30:00',
                'end' => '2025-04-21 19:15:00',
                'title' => 'Cooking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-21 19:30:00',
                'end' => '2025-04-21 19:50:00',
                'title' => 'Ironing clothes for tomorrow'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-21 20:30:00',
                'end' => '2025-04-21 22:00:00',
                'title' => 'Evening entertainment - movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-21 22:00:00',
                'end' => '2025-04-21 23:00:00',
                'title' => 'Reading before bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-20 00:00:00',
                'end' => '2025-04-20 23:59:00',
                'title' => 'Fridge on all day'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-20 00:00:00',
                'end' => '2025-04-20 23:59:00',
                'title' => 'Internet router always on'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-20 08:30:00',
                'end' => '2025-04-20 08:33:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-20 09:00:00',
                'end' => '2025-04-20 09:45:00',
                'title' => 'Making a big Sunday breakfast/brunch'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-20 10:00:00',
                'end' => '2025-04-20 11:30:00',
                'title' => 'Watching cartoons with kids / morning show'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-04-20 11:45:00',
                'end' => '2025-04-20 12:15:00',
                'title' => 'Sunday house cleaning - vacuuming'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-20 13:00:00',
                'end' => '2025-04-20 14:30:00',
                'title' => 'Browse internet and personal tasks'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-20 14:00:00',
                'end' => '2025-04-20 14:45:00',
                'title' => 'Cooling down living room - part 1'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-20 15:00:00',
                'end' => '2025-04-20 16:00:00',
                'title' => 'Watching videos on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-20 15:45:00',
                'end' => '2025-04-20 16:30:00',
                'title' => 'Cooling down living room - part 2'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-20 16:30:00',
                'end' => '2025-04-20 16:33:00',
                'title' => 'Afternoon tea time'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-20 18:00:00',
                'end' => '2025-04-20 18:10:00',
                'title' => 'Heating up leftovers for a quick dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-20 19:00:00',
                'end' => '2025-04-20 19:30:00',
                'title' => 'Preparing clothes for the week ahead'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-20 20:00:00',
                'end' => '2025-04-20 22:15:00',
                'title' => 'Sunday evening movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-20 22:15:00',
                'end' => '2025-04-20 23:00:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-19 00:00:00',
                'end' => '2025-04-19 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-19 00:00:00',
                'end' => '2025-04-19 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-19 08:00:00',
                'end' => '2025-04-19 08:03:00',
                'title' => 'Weekend morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-19 08:15:00',
                'end' => '2025-04-19 08:20:00',
                'title' => 'Heating up breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-19 09:00:00',
                'end' => '2025-04-19 10:30:00',
                'title' => 'Watching Saturday morning show'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-19 11:00:00',
                'end' => '2025-04-19 12:00:00',
                'title' => 'Saturday laundry'
            ],
            [
                'device_name' => 'Vacuum Cleaner',
                'start' => '2025-04-19 12:15:00',
                'end' => '2025-04-19 12:45:00',
                'title' => 'Weekend house cleaning'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-19 14:00:00',
                'end' => '2025-04-19 16:00:00',
                'title' => 'Casual computer Browse'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-19 15:00:00',
                'end' => '2025-04-19 17:00:00',
                'title' => 'Cooling down living room'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-19 17:30:00',
                'end' => '2025-04-19 17:33:00',
                'title' => 'Afternoon tea time'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-19 18:30:00',
                'end' => '2025-04-19 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'Iron',
                'start' => '2025-04-19 19:45:00',
                'end' => '2025-04-19 20:15:00',
                'title' => 'Ironing clothes for evening'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-19 20:30:00',
                'end' => '2025-04-19 23:00:00',
                'title' => 'Saturday evening movie'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-19 23:00:00',
                'end' => '2025-04-19 23:45:00',
                'title' => 'Reading in bed'
            ],
            [
                'device_name' => 'Fridge',
                'start' => '2025-04-18 00:00:00',
                'end' => '2025-04-18 23:59:00',
                'title' => 'Fridge operating continuously'
            ],
            [
                'device_name' => 'Router',
                'start' => '2025-04-18 00:00:00',
                'end' => '2025-04-18 23:59:00',
                'title' => 'Wi-Fi router active all day'
            ],
            [
                'device_name' => 'Coffee Maker',
                'start' => '2025-04-18 06:45:00',
                'end' => '2025-04-18 06:47:00',
                'title' => 'Early morning coffee'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-18 07:00:00',
                'end' => '2025-04-18 07:05:00',
                'title' => 'Warming up oatmeal for breakfast'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-18 07:20:00',
                'end' => '2025-04-18 08:00:00',
                'title' => 'Catching up on morning news'
            ],
            [
                'device_name' => 'Hair Dryer',
                'start' => '2025-04-18 08:05:00',
                'end' => '2025-04-18 08:10:00',
                'title' => 'Quick hair dry before work'
            ],
            [
                'device_name' => 'Computer',
                'start' => '2025-04-18 08:50:00',
                'end' => '2025-04-18 12:30:00',
                'title' => 'Work on PC - morning session'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-18 10:00:00',
                'end' => '2025-04-18 11:00:00',
                'title' => 'Morning cooling burst during work'
            ],
            [
                'device_name' => 'Water Boiler',
                'start' => '2025-04-18 11:00:00',
                'end' => '2025-04-18 11:03:00',
                'title' => 'Boiling water for tea'
            ],
            [
                'device_name' => 'Microwave',
                'start' => '2025-04-18 12:35:00',
                'end' => '2025-04-18 12:40:00',
                'title' => 'Lunch reheating'
            ],
            [
                'device_name' => 'Laptop',
                'start' => '2025-04-18 13:30:00',
                'end' => '2025-04-18 17:15:00',
                'title' => 'Afternoon tasks and meetings on laptop'
            ],
            [
                'device_name' => 'Air Conditioner',
                'start' => '2025-04-18 14:30:00',
                'end' => '2025-04-18 15:30:00',
                'title' => 'Afternoon cooling burst during work'
            ],
            [
                'device_name' => 'Washing Machine',
                'start' => '2025-04-18 17:30:00',
                'end' => '2025-04-18 18:30:00',
                'title' => 'Running a load of laundry'
            ],
            [
                'device_name' => 'Oven',
                'start' => '2025-04-18 18:45:00',
                'end' => '2025-04-18 19:30:00',
                'title' => 'Baking dinner'
            ],
            [
                'device_name' => 'TV',
                'start' => '2025-04-18 20:15:00',
                'end' => '2025-04-18 22:00:00',
                'title' => 'Watching a TV series'
            ],
            [
                'device_name' => 'Lamp',
                'start' => '2025-04-18 22:00:00',
                'end' => '2025-04-18 22:45:00',
                'title' => 'Evening reading with lamp'
            ],
        ];

        $startDate = new DateTimeImmutable('2024-05-01');
        $endDate = new DateTimeImmutable('2025-04-17');

        $interval = new DateInterval('P1D');

        $period = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

        foreach ($period as $currentDay) {
            $dayOfWeek = (int)$currentDay->format('N');
            $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);

            // Fridge (24/7)
            $fridgeStart = $currentDay->setTime(0, 0, 0);
            $fridgeEnd = $currentDay->setTime(23, 59, 59);
            $generatedLogs[] = [
                'device_name' => 'Fridge',
                'start' => $fridgeStart->format('Y-m-d H:i:s'),
                'end' => $fridgeEnd->format('Y-m-d H:i:s'),
                'title' => 'Fridge running',
            ];
            // Router (24/7)
            $routerStart = $currentDay->setTime(0, 0, 0);
            $routerEnd = $currentDay->setTime(23, 59, 59);
            $generatedLogs[] = [
                'device_name' => 'Router',
                'start' => $routerStart->format('Y-m-d H:i:s'),
                'end' => $routerEnd->format('Y-m-d H:i:s'),
                'title' => 'Internet active',
            ];

            // Computer/Laptop
            if (!$isWeekend || random_int(0, 4) > 2) {
                $pcName = random_int(0, 1) ? 'Computer' : 'Laptop';
                $hour = random_int(18, 19);
                $min = random_int(0, 30);
                $duration = random_int(90, 180); // 1.5 - 3h
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => $pcName,
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => "$pcName usage - evening",
                ];
            }

            // TV: most evenings
            if (random_int(0, 6) > 1) {
                $hour = random_int(19, 21);
                $min = random_int(0, 59);
                $duration = random_int(60, 150);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'TV',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'TV - evening show/movie',
                ];
            }

            // Washing Machine: 2-3x per week
            if (random_int(0, 6) < 3) {
                $hour = random_int(8, 16);
                $min = random_int(0, 59);
                $duration = random_int(60, 100);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Washing Machine',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Washing clothes',
                ];
            }

            // Microwave: 1-2x per day
            $microwaveUses = random_int(1, 2);
            for ($j = 0; $j < $microwaveUses; $j++) {
                $hour = ($j == 0) ? random_int(7, 9) : random_int(17, 21);
                $min = random_int(0, 59);
                $duration = random_int(2, 5);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Microwave',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Microwaving food',
                ];
            }

            // Coffee Maker: every morning, 6-8am, 3-6 min, sometimes a second coffee in afternoon (14-16)
            $coffeeStart = $currentDay->setTime(random_int(6, 8), random_int(0, 29), 0);
            $coffeeEnd = $coffeeStart->modify('+' . random_int(3, 6) . ' minutes');
            $generatedLogs[] = [
                'device_name' => 'Coffee Maker',
                'start' => $coffeeStart->format('Y-m-d H:i:s'),
                'end' => $coffeeEnd->format('Y-m-d H:i:s'),
                'title' => 'Morning coffee',
            ];
            if (random_int(0, 3) == 0) {
                $afternoonCoffee = $currentDay->setTime(random_int(14, 16), random_int(0, 29), 0);
                $afternoonCoffeeEnd = $afternoonCoffee->modify('+' . random_int(3, 6) . ' minutes');
                $generatedLogs[] = [
                    'device_name' => 'Coffee Maker',
                    'start' => $afternoonCoffee->format('Y-m-d H:i:s'),
                    'end' => $afternoonCoffeeEnd->format('Y-m-d H:i:s'),
                    'title' => 'Afternoon coffee',
                ];
            }

            // Hair Dryer: weekday mornings
            if (!$isWeekend && random_int(0, 9) < 6) {
                $start = $currentDay->setTime(random_int(7, 9), random_int(0, 29), 0);
                $end = $start->modify('+' . random_int(3, 7) . ' minutes');
                $generatedLogs[] = [
                    'device_name' => 'Hair Dryer',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Morning hair routine',
                ];
            }

            // Lamp: most evenings
            if (random_int(0, 6) > 0) {
                $hour = random_int(18, 23);
                $min = random_int(0, 59);
                $duration = random_int(45, 300);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Lamp',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Evening light',
                ];
            }

            // Oven: Dinner prep
            if (random_int(0, 1)) {
                $hour = random_int(14, 19);
                $min = random_int(0, 29);
                $duration = random_int(30, 120);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Oven',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Dinner prep',
                ];
            }

            // Water Boiler: 1-2 times/day
            $waterBoilerUses = random_int(1, 2);
            for ($k = 0; $k < $waterBoilerUses; $k++) {
                $hour = ($k == 0) ? random_int(6, 9) : random_int(18, 22);
                $min = random_int(0, 29);
                $duration = random_int(2, 5);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Water Boiler',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Boiling water',
                ];
            }

            // Air Conditioner: Apr-Sep
            $month = (int)$currentDay->format('m');
            if ($month >= 4 && $month <= 9 && random_int(0, 5) == 0) {
                $hour = random_int(15, 19);
                $min = random_int(0, 29);
                $duration = random_int(60, 200);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Air Conditioner',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Cooling the room',
                ];
            }

            // Heater: Oct-Mar
            if ($month <= 3 || $month >= 10) {
                for ($h = 0; $h < random_int(1, 2); $h++) {
                    $hour = $h == 0 ? random_int(6, 8) : random_int(18, 23);
                    $min = random_int(0, 59);
                    $duration = random_int(90, 310);
                    $start = $currentDay->setTime($hour, $min, 0);
                    $end = $start->modify("+$duration minutes");
                    $generatedLogs[] = [
                        'device_name' => 'Heater',
                        'start' => $start->format('Y-m-d H:i:s'),
                        'end' => $end->format('Y-m-d H:i:s'),
                        'title' => 'Room heating',
                    ];
                }
            }

            // Iron: weekend, late afternoon, 20-40 min
            if ($isWeekend && random_int(0, 5) == 0) {
                $hour = random_int(14, 19);
                $min = random_int(0, 29);
                $duration = random_int(20, 40);
                $start = $currentDay->setTime($hour, $min, 0);
                $end = $start->modify("+$duration minutes");
                $generatedLogs[] = [
                    'device_name' => 'Iron',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'title' => 'Ironing clothes',
                ];
            }
        }

        $generatedLogs = array_merge($generatedLogs, $logs);

        foreach ($generatedLogs as $logData) {
            $device = $deviceRepo->findOneBy(['name' => $logData['device_name']]);
            if (!$device) {
                continue;
            }

            $log = new DeviceUsageLog();
            $log->setDevice($device);
            $log->setStartedAt(new DateTimeImmutable($logData['start']));
            $log->setEndedAt(new DateTimeImmutable($logData['end']));
            $duration = $log->getEndedAt()->getTimestamp() - $log->getStartedAt()->getTimestamp();
            $log->setDuration($duration);
            $log->calculateEnergyUsage();
            $log->setTitle($logData['title']);
            $manager->persist($log);
        }

        $manager->flush();
    }
}