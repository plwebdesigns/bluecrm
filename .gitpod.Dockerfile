FROM gitpod/workspace-full

RUN composer install /workspace/bluecrm

RUN npm install