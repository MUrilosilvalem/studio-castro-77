# Studio Castro 77 - Portal de Podcast

Este repositório contém o código-fonte e a infraestrutura para o portal do Studio Castro 77, um portal de podcast profissional focado em autoridade na saúde.

## 🚀 Estrutura do Projeto

- `docker-compose.yml`: Configuração para deploy automático no Easypanel.
- `wp-content/plugins/studio-castro-core/`: Plugin customizado com CPTs e Shortcodes.
- `frontend-components.html`: Referência de componentes modernos (Tailwind + JS).
- `n8n/`: Fluxos de automação para integração com Google Sheets e notificações.

## 🛠️ Passo a Passo de Deploy

### 1. Preparação no GitHub
1. Crie um novo repositório no GitHub.
2. Faça o upload de todos os arquivos deste projeto.

### 2. Configuração no Easypanel
1. No seu dashboard do Easypanel, clique em **"Create Project"**.
2. Escolha **"App"** e selecione o seu repositório GitHub.
3. No campo **"Compose File"**, o Easypanel detectará automaticamente o `docker-compose.yml`.
4. Clique em **"Deploy"**.
5. Em **Service > Wordpress > Domains**, configure o seu domínio (ex: `studiocastro77.com.br`).

### 3. Configuração do WordPress
1. Acesse o painel `/wp-admin`.
2. Vá em **Plugins > Installed Plugins** e ative o **Studio Castro 77 Core**.
3. (Opcional) Utilize um tema compatível com Tailwind ou insira o script do CDN no seu `<head>`.

### 4. Configuração das Automações (n8n)
1. Importe os arquivos JSON da pasta `/n8n` para o seu servidor n8n.
2. Substitua o `YOUR_SHEET_ID` pelas IDs das suas planilhas do Google.
3. Configure as credenciais do Google Cloud e Telegram/WhatsApp.
4. Copie as URLs dos Webhooks geradas pelo n8n e substitua no arquivo `frontend-components.html` (ou no bloco correspondente no WordPress).

## 🎨 Identidade Visual
- **Títulos**: Montserrat
- **Textos**: Roboto
- **Cores**: 
    - Azul: `#154c79`
    - Verde: `#9eb53e`
    - Off-White: `#f4f6f8`

---
Desenvolvido com foco em alta conversão e autoridade científica.
