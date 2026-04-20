FROM wordpress:latest

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o plugin customizado e o mu-plugins (reset de senha)
COPY wp-content/plugins/studio-castro-core /var/www/html/wp-content/plugins/studio-castro-core
COPY wp-content/mu-plugins /var/www/html/wp-content/mu-plugins

# Ajusta permissões (opcional, WordPress lida bem com isso, mas é boa prática)
# RUN chown -R www-data:www-data /var/www/html/wp-content/plugins/studio-castro-core

# Expõe a porta 80
EXPOSE 80
