# Delight WhatsApp - Plugin WordPress

Um plugin WordPress avançado que adiciona um botão flutuante do WhatsApp com funcionalidades completas de analytics, escaneamento automático e integração com Google Analytics/GTM.

## 🚀 Funcionalidades

### ✨ Funcionalidades Principais
- **Botão flutuante personalizável** - Posicione o botão à esquerda ou direita da tela
- **Posição vertical ajustável** - Defina a distância do botão em relação à parte inferior da tela
- **Mensagem de saudação animada** - Exiba uma mensagem de boas-vindas opcional
- **Design responsivo** - Funciona perfeitamente em dispositivos móveis

### 🔍 Escaneamento Automático
- **Auto-detecção de GTM ID** - Escaneia automaticamente a página inicial em busca do Google Tag Manager
- **Auto-detecção de telefone** - Encontra automaticamente números brasileiros que começam com 55119
- **Preenchimento automático** - Preenche os campos automaticamente após o escaneamento

### 📊 Analytics Avançado
- **Dashboard completo** - Visualize estatísticas de cliques em tempo real
- **Gráficos interativos** - Acompanhe cliques dos últimos 7 dias
- **Métricas detalhadas** - Cliques hoje, semana, mês e total
- **Rastreamento de páginas** - Veja em quais páginas os usuários mais clicam

### 🎯 Integração com Marketing
- **Google Analytics** - Rastreie cliques automaticamente no GA
- **Google Tag Manager** - Envie eventos personalizados para o GTM
- **Rastreamento UTM** - Mantenha e envie parâmetros UTM para análise
- **Auto preenchimento de mensagem** - Inclua automaticamente título e URL da página na mensagem

### ⚙️ Configurações Flexíveis
- **Todas as opções podem ser ativadas/desativadas** individualmente
- **Interface administrativa intuitiva** com dashboard dedicado
- **Configurações granulares** para cada funcionalidade

## 📦 Instalação

### Instalação Automática
1. Faça login no seu painel do WordPress
2. Vá para **Plugins > Adicionar novo**
3. Pesquise por "Delight WhatsApp"
4. Clique em "Instalar agora" e depois "Ativar"

### Instalação Manual
1. Baixe o arquivo ZIP do plugin
2. Vá para **Plugins > Adicionar novo > Enviar plugin**
3. Selecione o arquivo ZIP e clique em "Instalar agora"
4. Ative o plugin

## 🛠️ Configuração

### Configuração Rápida
1. Vá para **Delight WhatsApp > Configurações**
2. Clique em **"Escanear Automaticamente"** para auto-detectar GTM e telefone
3. Configure as demais opções conforme desejado
4. Clique em **"Salvar alterações"**

### Configuração Manual
1. **Número do WhatsApp**: Digite no formato 55(11)99999-9999
2. **Posição**: Escolha esquerda ou direita
3. **GTM/GA**: Configure seus IDs de rastreamento
4. **Funcionalidades**: Ative/desative conforme necessário

## 📈 Dashboard

Acesse **Delight WhatsApp > Dashboard** para visualizar:

- **Métricas em tempo real**: Cliques hoje, semana, mês e total
- **Gráfico de tendências**: Visualize cliques dos últimos 7 dias
- **Análise de performance**: Acompanhe o engajamento dos usuários

## 🔧 Funcionalidades Técnicas

### Escaneamento Automático
O plugin escaneia automaticamente a página inicial do seu site procurando por:
- **GTM IDs**: Padrões como `GTM-XXXXXXX`
- **Números de telefone**: Números brasileiros começando com 55119
- **Múltiplos formatos**: Reconhece diferentes formatações de telefone

### Rastreamento UTM
- Captura automaticamente parâmetros UTM da URL
- Mantém os parâmetros durante a sessão
- Envia para GTM/GA para análise de origem de tráfego

### Auto Preenchimento de Mensagem
Quando ativado, inclui automaticamente na mensagem do WhatsApp:
- Título da página atual
- URL da página atual
- Mensagem personalizada: "Olá! Estou na página 'X' (URL) e gostaria de mais detalhes."

## 🎨 Personalização

### CSS Personalizado
O plugin permite personalização via CSS. Classes principais:
- `.delight-whatsapp-container` - Container do botão
- `.delight-whatsapp` - Link do botão
- `.delight-whatsapp-greeting` - Mensagem de saudação

### Hooks e Filtros
O plugin oferece hooks para desenvolvedores:
- `delight_whatsapp_before_button` - Antes do botão
- `delight_whatsapp_after_button` - Depois do botão
- `delight_whatsapp_phone_format` - Filtro para formato do telefone

## 📱 Compatibilidade

- **WordPress**: 5.0+
- **PHP**: 7.4+
- **Navegadores**: Todos os navegadores modernos
- **Dispositivos**: Desktop, tablet e mobile
- **Temas**: Compatível com todos os temas WordPress

## 🔒 Privacidade

O plugin coleta apenas dados necessários para analytics:
- URL da página onde o clique ocorreu
- Título da página
- Parâmetros UTM (se habilitado)
- IP do usuário (para estatísticas)
- User Agent (para análise de dispositivos)

Nenhum dado pessoal é coletado sem consentimento.

## 🆕 Changelog

### Versão 2.0.0
- ✅ **Nova funcionalidade**: Escaneamento automático de GTM e telefone
- ✅ **Nova funcionalidade**: Dashboard completo com analytics
- ✅ **Nova funcionalidade**: Auto preenchimento com informações da página
- ✅ **Nova funcionalidade**: Rastreamento UTM avançado
- ✅ **Melhoria**: Interface administrativa redesenhada
- ✅ **Melhoria**: Estrutura de código otimizada e modular
- ✅ **Melhoria**: Performance aprimorada

### Versão 1.0.5
- Melhorias na estrutura do código
- Organização em classes separadas
- Melhor compatibilidade com temas
- Correções de bugs menores

## 🤝 Suporte

Para suporte técnico:
- **Website**: [robertogrozinski.com](https://www.robertogrozinski.com)
- **Email**: roberto.grozinski@gmail.com
- **GitHub**: [github.com/robertogrozinski/delight-whatsapp](https://github.com/robertogrozinski/delight-whatsapp)

## 📄 Licença

Este plugin é licenciado sob a GPL v2 ou posterior.

## 🙏 Contribuições

Contribuições são bem-vindas! Por favor:
1. Faça um fork do projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

---

**Desenvolvido com ❤️ por [Roberto Grozinski](https://www.robertogrozinski.com)**