jquery-cms-2
============

#Os primeiros passos

Este é um projeto que irá auxilia-lo a desenvolver mais rapidamente as estruturas de seus backends. 

Este vídeo demonstrará os recursos do jQueryCms:
<a href='http://www.youtube.com/watch?v=HARofjn9G8Q'>Demonstração dos recursos após instalação - primeiros passos</a>

Premissas
==============

Você deve possuir um banco de dados relacional mysql. É de grande importância que o as tabelas do banco de dados possuam relacionamentos, uma vez que, as associações serão replicadas ao gerador de código e em relações entre formulários (através de campos como select ao invés de input de texto).

Permissões e Ambientes
==============

No ambiente de desenvolvimento necessitaremos de permissão de escrita para a pasta raiz, uma vez que o sistema irá criar várias sub-pastas e arquivos.

No ambiente de produção, somente será necessário permissão de escrita nas pastas:
~/cache
~/jquerycms/upload

