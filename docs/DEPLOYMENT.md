# Production Deployment Guide (oetzistrom.cosylab.at)

Production server path:

```
/opt/oetzi
```

## Deployment Steps

```bash
ssh flo@oetzistrom
cd /opt/oetzi
./deploy.sh
```

## What the deployment script does

1. `git pull`
2. Builds Docker images
3. Starts containers
4. Composer install
5. NPM install + build
6. Symfony cache clear

## Notes

Doctrine migrations are currently not in sync with the production schema; until aligned, deploy schema changes via SQL dump.
