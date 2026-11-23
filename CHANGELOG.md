<p align="center"><a href="https://webeponto.com.br" target="_blank"><img src="/resources/svg/site/logo/default.svg" width="400" alt="JRJ Assessoria"/><a></p>

# Changelog
Veja abaixo o registro das atualizações do site da JRJ Assessoria e seus versionamentos, organizados por:

- Feat: Implementação de uma nova funcionalidade;
- Fix: Correção de um bug;
- Style: Implementações e atualizações relacionadas ao estilo de uma ferramenta/página;
- Refactor: Refatoração de uma área específica de um código;
- Test: Funcionalidades experimentais ou implementações para teste;
- Docs: Qualquer coisa relacionada à documentação do projeto;
- Chore: Manutenção regular de um código.

[Veja o histórico de mudanças](https://github.com/webeponto/jrj_assessoria/commits).

## 1.3.2

### Feat
- Páginas:
	- Nova página de serviço adicionada: *Instalação e Manutenção de Controle de Temperatura*;

### Fix
- Componentes:
	- `resources/components/window/header.blade.php`: Corrigido a maneira como é renderizada os itens do menu dropdown de serviços, que apresentava erros quando os títulos dos serviços eram muito longos;

<!------------------------------------------------------>

## 1.3.1

### Feat
- Geral:
	- Alterado o título padrão do site e sua descrição para melhorar o SEO e a apresentação nos motores de busca;

### Fix
- Geral:
	- Corrrigido alguns erros de ortografia presentes em textos do site;

### Chore
- Geral:
	- Removido alguns arquivos que não eram necessários para o funcionamento do site;


<!------------------------------------------------------>

## 1.3.0

### Feat
- Geral:
	- `app/Http/Middleware/GlobalConfig.php`: Definido variáveis globais para os telefones que não são relacionados ao WhatsApp;
- Componentes:
	- `resources/components/content/cta.blade.php`: O antigo componente *contact-us* foi renomeado para *cta* e devidamente componentizado para poder ser utilizado em mais páginas, com textos personalizáveis;
	- `resources/components/system/loading.blade.php`: O sistema de carregamento foi componentizado com um novo parâmetro que permite aplicar diferentes tipos de animações de saída do loading, criando mais variedades para o componente. As frases de efeito também foram reformuladas;

### Fix
- Assets:
	- Otimizado inúmeras imagens presentes no escopo do projeto;

### Chore
- Componentes:
	- `resources/components/content/services.blade.php`: O título da aba de banner de serviços foi alterado para um que visa informar de maneira mais objetiva os tipos de serviços oferecidos;


<!------------------------------------------------------>

## 1.2.0

### Feat
- Páginas:
	- `resources/pages/about.blade.php`: No banner da página *Sobre Nós*, foram atribuídas as imagens e conteúdos de texto corretos do site;
- Componentes:
	- `resources/components/window/header.blade.php`: Foi desenvolvido um submenu dropdown para o botão de serviços, facilitando a escolha e navegação de serviços específicos oferecidos pela empresa. Ainda é possível navegar para uma página geral de serviços ao clicar sobre o botão principal, caso o usuário queira explorar todas as opções disponíveis;
	- `resources/components/system/loading.blade.php`: Foi desenvolvido do zero um novo design para o sistema de carregamento da página, com frases divertidas que indicam o carregamento da página com um layout imersivo e totalmente novo;

### Fix
- WPF3:
	- `resources/scss/wpf/vendor/vars.scss`: Corrigido um erro na forma como algumas funções de classes podiam processar cores, que não possibilitavam a devida aplicação em contextos responsivos (utilizando a diretiva *mob:*);
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Consertado um erro onde era extremamente inconsistente alterar a cor de uma borda (com a função de classe *bc()*) sob a diretiva *hover:*;
	- `resources/js/dynamic/render.js`: Aprimorado a detecção de classes utilitárias do WPF3 em comentários;
- Componentes:
	- `resources/components/window/header.blade.php`: Alterado o link do botão *Faça um Orçamento* para apontar diretamente para o WhatsApp da empresa, como deveria ser;
	- `resources/components/content/services.blade.php`: Corrigido um problema onde, em alguns casos, as figuras representativas dos serviços não seguiam os padrões de cores definidos no design do resto do site;

### Style
- Assets:
	- Inseridos inúmeros novos ícones e imagens no escopo do projeto;
- Componentes:
	- `resources/components/window/header.blade.php`: Ajustado o tamanho da fonte de certos elementos em contextos responsivos para garantir uma melhor leitura e usabilidade em dispositivos móveis;
	- `resources/components/window/header.blade.php`: Foi adicionado alguns elementos de acessibilidade nos cabeçalhos do site em contextos responsivos para que a leitura fosse consistente e clara para todos os usuários, independente do plano de fundo da página;

### Chore
- Assets
	- `resources/videos/1.mp4`: Removido um vídeo que não fazia parte do escopo do projeto;

<!------------------------------------------------------>

## 1.0.0

### Style
- Componentes:
	- `resources/components/window/header.blade.php`: Ajustado alguns tamanhos de fonte e espaçamentos para melhorar a navegação do banner na página principal;


<!----------------------------------------------------------------------------------------->

## 0.6.0

### Feat
- WPF3:
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Adicionado suporte às classes utilitárias *.w-none* e *.h-none* nas variações para *-min* e *-max*, que resetam as propriedades *width* e *height* para *auto*, respectivamente para valores mínimos e máximos;
	- `resources/js/wpf/dynamic/wpf.config.yaml`: As classes utilitárias para *padding* e *margin* agora aceitam os valores *dvh* e *dvw*, quando elas fazem sentido e são aplicáveis;
	- `resources/js/wpf/dynamic/render.js`: Aperfeiçoado os métodos de detecção de classes utilitárias dentro de padrões do *Blade/Laravel*;
- Assets:
	- `resources/svg/logo/default-complex.svg`: Atualizado a logo interativa do site para automaticamente detectar quando o menu mobile do site for aberto, automaticamente alterando sua cor para uma versão escura da logo;
- Componentes:
	- `resources/components/window/header.blade.php`: Recortado a versão mobile do menu principal do site;

### Style
- Assets:
	- Inserido novas imagens no escopo do projeto;

### Refactor
- Componentes:
	- `resources/components/window/header.blade.php`: Revisto a lógica de como o a introdução de cada página funciona, levando em consideração casos como páginas que possuem breadcrumbs, ou páginas internas, ou a página principal do site, com suas devidas variações de funcionalidades e estilos;
	- `resources/components/window/header.blade.php`: Removido propriedades do componente que estavam depreciadas e não eram mais utilizadas;

<!----------------------------------------------------------------------------------------->

## 0.5.0

### Feat
- WPF3:
	- `resources/js/wpf/dynamic/render.js`: Melhorado o reconhecimento do interpretador do WPF3 para detectar classes utilitárias do WPF3 em elementos JavaScript;
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Adicionado suporte à uma classe utilitária *.center-reset* que remove qualquer comportamento imposto pelas funções de alinhamento absoluto *.center(value)*;
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Inserido uma nova função de classe chamada *.obj-pos()* que rege a propriedade *object-position* em elementos HTML, permitindo o posicionamento preciso de imagens e vídeos dentro de seus contêineres. Para utilizá-la, você pode passar dois parâmetros (ex: *.obj-pos(50%,30%)*) ou palavras-chave (ex: *.obj-pos(top-left)*);

### Style
- Assets:
	- `resources/svg/logo/default-complex.svg`: Realizado algumas melhorias no código e lógica para exibição da logo interativa e responsiva do site;
	- Inseridos novos ícones SVG no escopo do projeto;

### Fix
- WPF3:
	- `resources/js/wpf/dynamic/render.js`: Endereçado um sério problema com os ritmos de reconhecimento e aplicação da função de interpretação de classes do renderizador, que poderia resultar em alguns problemas de performance ao longo do seu tempo de aplicação;

### Refactor
- Componentes
	- Melhorado a lógica PHP de inúmeros componentes por todo o site;


<!----------------------------------------------------------------------------------------->

## 0.3.0

### Feat
- Componentes:
	- `resources/views/components/window/header.blade.php`: Dado início ao processo de responsividade do site;

### Style
- Assets:
	- `resources/svg/logo/default-complex.svg`: Criado um sistema de logo alternativa, que se adapta aos breakpoints diretamente pelas classes e grupos do SVG, sem a necessidade de múltiplos arquivos. Ela automaticamente se reajusta e se centraliza conforme a necessidade e a resolução do dispositivo do usuário;

### Fix
- WPF3:
	- `resources/js/wpf/dynamic/render.js`: Corrigido um bug que fazia com que as regras CSS geradas para os breakpoints fossem aplicadas na ordem errada, causando conflitos e sobreposições indesejadas. Agora, as regras são aplicadas do maior para o menor breakpoint, garantindo que estilos específicos para dispositivos menores prevaleçam corretamente;

### Refactor
- WPF3
	- `resources/js/wpf/dynamic/render.js`: Foi feito melhorias em diversas partes da função *GenerateCSS()*, principalmente na parte que lida com os breakpoints, para garantir que o código renderizado seja mais otimizado e preciso nas suas funcionalidades;


<!----------------------------------------------------------------------------------------->

## 0.2.0

### Feat
- Páginas:
	- `resources/views/pages/home.blade.php`: Recortado a versão base da página inicial do site, assim como componentizado suas devidas partes em diferentes seções;
- Componentes:
	- `resources/views/components/content/services.blade.php`: Finalizado a integração do modo *banner* do componente de serviços, com a navegação completa e funcional do componente;
	- `resources/views/components/content/services.blade.php`: Finalizado a componentização do componente de serviços;
	- `resources/views/components/window/header.blade.php`: Inserido efeitos para demarcar a página atual que o usuário está navegando;
	- `resources/views/components/window/header.blade.php`: Finalizado a componentização do cabeçalho do site;
	
- WPF3:
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Adicionado suporte à classe utilitária *.fs* para definir a propriedade *font-style* em elementos HTML. Ela segue o exato mesmo padrão que as classes para a propriedade *font-size*, porém com os parâmetros referentes ao estilo da fonte (normal, italic ou oblique);
	- `resources/js/wpf/dynamic/wpf.config.yaml`: Também foi adicionado suporte às classes utilitárias referentes a justify-self e align-self, seguindo o mesmo padrão antigo que era presente no WPF2 (*.vs* e *.hs*), tanto para as classes *.dp-flex* como para *.dp-flex-inline*;

- Assets:
	- Inserido inúmeros novos ícones SVG e imagens no escopo do projeto;

### Fix
- Páginas:
	- `resources/views/pages/home.blade.php`: Endereçado um problema onde as bibliotecas de banner e carrossel (como Swiper.js e Slick.js) não estavam funcionando corretamente devido a um erro na estruturação base do HTML;

### Refactor
- Assets
	- `resources/fonts`: Alterado o formato de todas as fontes do projeto para o formato `.woff2`, visando uma melhor performance e otimização do carregamento das mesmas;


<!----------------------------------------------------------------------------------------->

## 0.1.0

### Feat
- Páginas:
	- `resources/views/pages/home.blade.php`: Recortado a versão base da página inicial do site, assim como componentizado suas devidas partes em diferentes seções;
- Assets:
	- Inserido inúmeros novos ícones SVG e imagens no escopo do projeto;

### Fix
- WPF3:
	- `resources/scss/wpf/vendor/vars.scss`: Corrigido um bug que impedia a aplicação de funções de classe referentes à cores com diferentes variáveis em diferentes situações comportamentais de um único elemento.

<!----------------------------------------------------------------------------------------->

## 0.0.0
- Commit inicial do Projeto.
