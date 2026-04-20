FROM wordpress:latest

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o plugin customizado para o diretório de plugins do WordPress
COPY wp-content/plugins/studio-castro-core /var/www/html/wp-content/plugins/studio-castro-core

# Ajusta permissões (opcional, WordPress lida bem com isso, mas é boa prática)
# RUN chown -R www-data:www-data /var/www/html/wp-content/plugins/studio-castro-core

# Expõe a porta 80
EXPOSE 80
