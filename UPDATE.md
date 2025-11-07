# 🔄 Atualização - Página de Perfil

## ✨ Nova Funcionalidade Adicionada

Implementada página de perfil completa para todos os usuários (admin e users).

## 📦 Arquivos Novos/Modificados

### Novos Arquivos:
- ✅ `profile.php` - Página de perfil do usuário

### Arquivos Modificados:
- ✅ `includes/header.php` - Adicionado botão "Perfil" no menu

## 🚀 Como Atualizar em Produção

### Opção 1: Upload Manual (Recomendado)

1. **Baixe os arquivos atualizados:**
   - `profile.php`
   - `includes/header.php`

2. **Envie via FTP/SFTP** para o servidor, substituindo os existentes

3. **Teste:** Acesse `https://nbtech.pt/profile.php`

### Opção 2: Copiar e Colar

Se tiver acesso ao editor de arquivos do cPanel:

1. Crie o arquivo `profile.php` na raiz
2. Copie todo o conteúdo do arquivo local
3. Edite `includes/header.php` com as alterações

## 🎯 Funcionalidades da Página de Perfil

### Para TODOS os usuários:

✅ **Alterar Informações Básicas**
- Nome completo
- Email

✅ **Alterar Senha**
- Requer senha atual
- Nova senha (mínimo 6 caracteres)
- Confirmação de senha

✅ **Gerenciar Foto de Perfil**
- Upload de nova foto (JPG, PNG, GIF)
- Máximo 5MB
- Remover foto atual
- Fallback para Gravatar

✅ **Visualizar Informações da Conta**
- Data de cadastro
- Último acesso
- Tipo de conta (Admin/User)

### Apenas ADMIN pode:
- Gerenciar outros usuários (já existente em `/admin/users.php`)
- Promover/remover admin de outros usuários
- Deletar usuários

## 🔒 Segurança Implementada

- ✅ Validação de senha atual antes de alterar
- ✅ Verificação de email duplicado
- ✅ Validação de tipo de arquivo (apenas imagens)
- ✅ Limite de tamanho de arquivo (5MB)
- ✅ Nomes de arquivo únicos (evita sobrescrita)
- ✅ Proteção contra XSS
- ✅ Usuário só pode editar próprio perfil

## 📱 Interface

- ✅ Design responsivo (mobile-friendly)
- ✅ Integrado com Tailwind CSS
- ✅ Mensagens de sucesso/erro
- ✅ Preview da foto de perfil
- ✅ Botão "Perfil" no header

## 🧪 Como Testar

1. **Login como usuário normal:**
   - Acesse o perfil
   - Altere nome e email
   - Altere a senha
   - Faça upload de uma foto

2. **Login como admin:**
   - Mesmas funcionalidades
   - Verifique badge "Administrador"

3. **Teste de segurança:**
   - Tente alterar senha com senha atual errada (deve falhar)
   - Tente usar email já existente (deve falhar)
   - Tente fazer upload de arquivo não-imagem (deve falhar)

## ⚠️ Importante

- A pasta `uploads/profile_pics/` deve ter permissão 755
- Certifique-se de que o PHP pode escrever nessa pasta
- Fotos antigas são automaticamente deletadas ao fazer upload de nova

## 🎨 Localização do Botão Perfil

O botão "Perfil" aparece no header, ao lado do botão "Logout", para todos os usuários logados.

## 📊 Benefícios

1. **Autonomia:** Usuários podem gerenciar suas próprias informações
2. **Segurança:** Podem alterar senha quando quiserem
3. **Personalização:** Upload de foto de perfil
4. **Profissional:** Interface moderna e intuitiva
5. **Produção:** Pronto para uso imediato

## ✅ Checklist de Deploy

- [ ] Fazer backup dos arquivos atuais
- [ ] Enviar `profile.php` para a raiz
- [ ] Atualizar `includes/header.php`
- [ ] Verificar permissões da pasta `uploads/`
- [ ] Testar login e acesso ao perfil
- [ ] Testar alteração de senha
- [ ] Testar upload de foto
- [ ] Confirmar que tudo funciona

## 🆘 Solução de Problemas

### Erro ao fazer upload de foto
```bash
chmod 755 uploads/profile_pics/
```

### Botão perfil não aparece
- Limpe o cache do navegador
- Verifique se o arquivo `includes/header.php` foi atualizado

### Erro ao alterar senha
- Verifique se está digitando a senha atual correta
- Nova senha deve ter pelo menos 6 caracteres

## 🎉 Pronto!

A funcionalidade está completa e pronta para produção. Basta fazer o upload dos arquivos! 🚀
