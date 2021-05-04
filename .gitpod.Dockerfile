FROM gitpod/workspace-full

RUN composer install -d /workspace/bluecrm

RUN npm install