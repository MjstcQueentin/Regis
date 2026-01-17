FROM php:8.5-apache-trixie
WORKDIR /var/www/html

# Copy application files
COPY *.php .
COPY assets ./assets
COPY core/ ./core/

# Create projects directory
RUN mkdir projects
VOLUME /var/www/html/projects

# Enable writing permissions for the web server user
RUN chown -R www-data /var/www/html