FROM php:8.5-apache-trixie
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
WORKDIR /var/www/html

# Copy application files
COPY app/ /var/www/html/app/
COPY public/*.php /var/www/html/public/
COPY public/assets/ /var/www/html/public/assets/

# Create projects directory
RUN mkdir /var/www/html/public/projects
VOLUME /var/www/html/public/projects

# Set the document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable writing permissions for the web server user
RUN chown -R www-data /var/www/html
