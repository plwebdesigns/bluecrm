FROM gitpod/workspace-full

RUN composer install

RUN npm install