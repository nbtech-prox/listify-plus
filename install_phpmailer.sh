#!/bin/bash
# Script para instalar PHPMailer sem Composer

echo "Instalando PHPMailer..."

# Criar diretório vendor se não existir
mkdir -p vendor/phpmailer

# Baixar PHPMailer do GitHub
cd vendor/phpmailer
wget https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.9.1.zip -O phpmailer.zip

# Descompactar
unzip phpmailer.zip

# Renomear pasta
mv PHPMailer-6.9.1 phpmailer

# Limpar
rm phpmailer.zip

echo "PHPMailer instalado com sucesso!"
echo "Localização: vendor/phpmailer/phpmailer/"
