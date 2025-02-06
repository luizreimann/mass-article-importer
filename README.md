# Mass Article Importer

## Descrição
**Mass Article Importer** é um plugin para WordPress que permite importar artigos em massa a partir de um arquivo **XLSX**. Ele agenda automaticamente os artigos para publicação em intervalos configuráveis.

## Requisitos
- **WordPress:** 5.0+
- **PHP:** 7.2+
- **Biblioteca:** [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/latest/) (já instalada via Composer)

## Instalação
1. Faça o upload do diretório do plugin para `wp-content/plugins/`.
2. Se preferir, clone o repositório em ZIP e utilize o importador de plugins do WordPress.
3. Ative o plugin no painel administrativo do WordPress.

## Como Usar
1. No painel do WordPress, vá até **Importar Artigos**.
2. Faça o upload de um arquivo **XLSX**.
3. Escolha a **Data/Hora Inicial** para o primeiro post.
4. Selecione o **intervalo de publicação** entre os posts.
5. Clique em **Importar**.

## Formato do Arquivo XLSX
O arquivo deve conter os seguintes campos na primeira linha (cabeçalho):

| Coluna | Descrição |
|--------|------------|
| UUID | Ignorado |
| Title | Título do artigo |
| Content | Conteúdo em HTML |
| Excerpt | Resumo do artigo |
| Description | Descrição para SEO |
| Keywords | Palavras-chave separadas por vírgula |
| Focus keyword | Palavra-chave principal |

A importação ocorre da **segunda até a última linha** do arquivo.

## Funcionalidades
✅ Importação em massa de artigos 📄  
✅ Agendamento automático 📅  
✅ Suporte a SEO (descrição, palavras-chave) 🔍  
✅ Interface amigável no painel do WordPress 🎨  

## Suporte
Para suporte, entre em contato com **Luiz Reimann** pelo site [https://luizreimann.dev](https://luizreimann.dev).