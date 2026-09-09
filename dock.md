PS C:\Users> cd ..
PS C:\> cd laravel
PS C:\laravel> ls


    Directorio: C:\laravel


Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
d-----      9/09/2026  10:01 a. m.                node
d-----       9/09/2026  9:44 a. m.                PeliHuellas
d-----      19/08/2026  7:52 a. m.                tecnospeed


PS C:\laravel> cd pelihuellas
PS C:\laravel\pelihuellas>  $env:Path +=";C:\laravel\node"
PS C:\laravel\pelihuellas> winget install Schniz.fnm
Se encontró un paquete existente ya instalado. Intentando actualizar el paquete instalado...
No se ha encontrado ninguna actualización disponible.
No hay versiones más recientes del paquete disponibles en las fuentes configuradas.
PS C:\laravel\pelihuellas> npm install
npm : No se puede cargar el archivo C:\laravel\node\npm.ps1 porque la ejecución de scripts está deshabilitada en este
sistema. Para obtener más información, consulta el tema about_Execution_Policies en
https:/go.microsoft.com/fwlink/?LinkID=135170.
En línea: 1 Carácter: 1
+ npm install
+ ~~~
    + CategoryInfo          : SecurityError: (:) [], PSSecurityException
    + FullyQualifiedErrorId : UnauthorizedAccess
PS C:\laravel\pelihuellas> cd ..
PS C:\laravel> cd .\node\
PS C:\laravel\node> ls


    Directorio: C:\laravel\node


Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
d-----      9/09/2026  10:01 a. m.                node_modules
-a----      9/09/2026  10:01 a. m.          56595 CHANGELOG.md
-a----      9/09/2026  10:01 a. m.            334 corepack
-a----      9/09/2026  10:01 a. m.            218 corepack.cmd
-a----      9/09/2026  10:01 a. m.           3094 install_tools.bat
-a----      9/09/2026  10:01 a. m.         160555 LICENSE
-a----      9/09/2026  10:01 a. m.       93580104 node.exe
-a----      9/09/2026  10:01 a. m.            702 nodevars.bat
-a----      9/09/2026  10:01 a. m.           2073 npm
-a----      9/09/2026  10:01 a. m.            538 npm.cmd
-a----      9/09/2026  10:01 a. m.           1700 npm.ps1
-a----      9/09/2026  10:01 a. m.           2073 npx
-a----      9/09/2026  10:01 a. m.            538 npx.cmd
-a----      9/09/2026  10:01 a. m.           1700 npx.ps1
-a----      9/09/2026  10:01 a. m.          42703 README.md


PS C:\laravel\node>

PS C:\laravel\node> PS C:\laravel\node> node.exe
Get-Process : No se encuentra ningún parámetro de posición que acepte el argumento 'node.exe'.
En línea: 1 Carácter: 1
+ PS C:\laravel\node> node.exe
+ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    + CategoryInfo          : InvalidArgument: (:) [Get-Process], ParameterBindingException
    + FullyQualifiedErrorId : PositionalParameterNotFound,Microsoft.PowerShell.Commands.GetProcessCommand

PS C:\laravel\node> C:\laravel\node\node.exe --version
v24.21.0
PS C:\laravel\node> C:\laravel\node\npm --version
PS C:\laravel\node> cd ..
PS C:\laravel> cd pelihuellas
PS C:\laravel\pelihuellas> C:\laravel\node\npm --version
PS C:\laravel\pelihuellas> npm install
npm : No se puede cargar el archivo C:\laravel\node\npm.ps1 porque la ejecución de scripts está deshabilitada en este
sistema. Para obtener más información, consulta el tema about_Execution_Policies en
https:/go.microsoft.com/fwlink/?LinkID=135170.
En línea: 1 Carácter: 1
+ npm install
+ ~~~
    + CategoryInfo          : SecurityError: (:) [], PSSecurityException
    + FullyQualifiedErrorId : UnauthorizedAccess
PS C:\laravel\pelihuellas>