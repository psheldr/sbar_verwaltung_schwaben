ARG PHP_VERSION

FROM php:${PHP_VERSION}-apache

ENV TZ=Europe/Berlin
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN printf '[Date]\ndate.timezone="%s"\n', $TZ > /usr/local/etc/php/conf.d/tzone.ini

RUN apt-get update
RUN apt-get -y install locales

# Set the locale
RUN sed -i '/de_DE@euro/s/^# //g' /etc/locale.gen && locale-gen

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions bcmath bz2 calendar dba exif gd gettext imap intl ldap mysqli pdo_mysql pspell shmop soap sockets sysvmsg sysvsem sysvshm tidy timezonedb xsl zip