<?php   
/*   
 +--------------------------------------------------------------------------+  
 | phpMyBackupPro                                                           | 
 +--------------------------------------------------------------------------+  
 | Copyright (c) 2004-2007 by Dirk Randhahn                                 | 
 | http://www.phpMyBackupPro.net                                            |  
 | version information can be found in definitions.php.                     |  
 |                                                                          |  
 | This program is free software; you can redistribute it and/or            |  
 | modify it under the terms of the GNU General Public License              | 
 | as published by the Free Software Foundation; either version 2           |                                                                                   
 | of the License, or (at your option) any later version.                   | 
 |                                                                          | 
 | This program is distributed in the hope that it will be useful,          |  
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           | 
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |  
 | GNU General Public License for more details.                             |    
 |                                                                          |   
 | You should have received a copy of the GNU General Public License        |   
 | along with this program; if not, write to the Free Software              | 
 | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,USA.| 
 +--------------------------------------------------------------------------+   
 */
/*
 +--------------------------------------------------------------------------+  
 | phpMyBackupPro Spanisch translation                                      |   
 +--------------------------------------------------------------------------+    
 | Spanish translation 2.1                                                  |
 | July 2006 by Ruben Dario PICCATO, rpiccato@graficastuning.com.ar         |
 | September 2007 by Juan Jose Gonzalez, www.todo-mods.com                  |
 +--------------------------------------------------------------------------+
*/            
 
/*basic data*/  
define('BD_LANG_SHORTCUT',"sp"); // used for the php function setlocale() (http://www.php.net/setlocale)
define('BD_DATE_FORMAT',"%x %X"); // used for the php function strftime() (http://www.php.net/strftime) 
define('BD_CHARSET_HTML',"ISO-8859-1"); // the charset used in you language for html     
define('BD_CHARSET_EMAIL',"ISO-8859-1"); // the charset used in your langauge for MIME-emails  

/*functions.inc.php*/   
define('F_START',"Inicio");  
define('F_CONFIG',"Configuración");
define('F_IMPORT',"Importar");  
define('F_BACKUP',"Backup");  
define('F_SCHEDULE',"Backup Programado");  
define('F_DB_INFO',"Info BD");   
define('F_SQL_QUERY',"Consultas SQL");  
define('F_HELP',"Ayuda");       
define('F_LOGOUT',"Salir");      
define('F_FOOTER',"Visite %sphpMyBackupPro%s para nuevas revisiones y noticias."); 
define('F_NOW_AVAILABLE',"Una nueva versión de phpMyBackupPro está disponible en %s".PMBP_WEBSITE."%s");   
define('F_SELECT_DB',"Seleccione las bases de datos para backup"); 
define('F_SELECT_ALL',"Seleccionar todas");  
define('F_COMMENTS',"Comentarios");  
define('F_EX_TABLES',"Exportar tablas");
define('F_EX_DATA',"Exportar datos");  
define('F_EX_DROP',"Añadir 'drop table'");   
define('F_EX_COMP',"Compresión");      
define('F_EX_OFF',"ninguna");    
define('F_EX_GZIP',"gzip");   
define('F_EX_ZIP',"zip");  
define('F_FTP_1',"FTP falló la conexión al servidor");  
define('F_FTP_2',"Falló la identificación como usuario"); 
define('F_FTP_3',"FTP falló la subida");       
define('F_FTP_4',"Archivo correctamente subido");  
define('F_FTP_5',"FTP falló el borrado del archivo '%s' ."); 
define('F_FTP_6',"Archivo '%s' correctamente borrado en servidor  FTP");  
define('F_FTP_7',"Archivo '%s' no disponible en servidor FTP"); 
define('F_MAIL_1',"Un destinatario de correo es erróneo");  
define('F_MAIL_2',"Este correo fué enviado por phpMyBackupPro ".PMBP_VERSION." en ".PMBP_WEBSITE);  
define('F_MAIL_3',"Pueden ser leídos");   
define('F_MAIL_4',"MySQL backup desde");       
define('F_MAIL_5',"El correo no puede ser enviado");  
define('F_MAIL_6',"Archivos enviados por mail correctamente a "); 
define('F_YES',"si");   
define('F_NO',"no");    
define('F_DURATION',"Duración");   
define('F_SECONDS',"segundos");   
define('F_WRONG_FILE',"Esto no parece un archivo .zip correcto, pero puede ser un fallo en phpMyBackupPro");  

/*index.php*/  
define('I_SQL_ERROR',"ERROR: Por favor ingrese correctamente sus datos MySQL  en 'configuración'!"); 
define('I_NAME',"Esto es phpMyBackupPro");  
define('I_WELCOME',"phpMyBackupPro es software libre licenciado bajo GNU GPL.<br>  
Para ayuda en línea visite %s.<br><br>    
Seleccione en el menú superior lo que desee hacer!<br>Si es su primera vez utilizando phpMyBackupPro deberá comenzar con la configuración!<br>Los permisos del directorio 'export' y del archivo 'global_conf.php'  deben establecerse a CHMOD 0777."); 
define('I_CONF_ERROR',"El archivo ".PMBP_GLOBAL_CONF." no se puede escribir, Cambia sus permisos a CHMOD 0777!"); 
define('I_DIR_ERROR',"El directorio ".PMBP_EXPORT_DIR." no se puede escribir, Cambia sus permisos a CHMOD 0777!");   
define('PMBP_I_INFO',"Información de sistema");  
define('PMBP_I_SERVER',"Servidor");   
define('PMBP_I_TIME',"Fecha");   
define('PMBP_I_PHP_VERS',"PHP Version");  
define('PMBP_I_MEM_LIMIT',"PHP Memory Limit");   
define('PMBP_I_SAFE_MODE',"Safe Mode activado");   
define('PMBP_I_FTP',"es posible transferencia FTP");   
define('PMBP_I_MAIL',"correos enviables"); 
define('PMBP_I_GZIP',"compresión gzip posible");  
define('PMBP_I_SQL_SERVER',"Servidor MySQL"); 
define('PMBP_I_SQL_CLIENT',"Cliente MySQL");                                                
define('PMBP_I_NO_RES',"*No se puede recuperar*");  
define('PMBP_I_LAST_SCHEDULED',"Ultimo backup programado"); 
define('PMBP_I_LAST_LOGIN',"Ultima conexión");  
define('PMBP_I_LAST_LOGIN_ERROR',"Ultima identificación incorrecta");  

/*config.php*/   
define('C_SITENAME',"nombre del sitio"); 
define('C_LANG',"lenguaje");  
define('C_SQL_HOST',"Nombre del host MySQL"); 
define('C_SQL_USER',"Nombre de usuario MySQL"); 
define('C_SQL_PASSWD',"contraseña MySQL");
define('C_SQL_DB',"solo esta base de datos");
define('C_FTP_USE',"guardar backup por FTP?");
define('C_FTP_BACKUP',"usar directorio de backup?"); 
define('C_FTP_REC',"directorios de backup recursivos?");  
define('C_FTP_SERVER',"servidor FTP (url o IP)");  
define('C_FTP_USER',"nombre de usuario FTP"); 
define('C_FTP_PASSWD',"contraseña FTP");   
define('C_FTP_PATH',"Directorio FTP");    
define('C_FTP_PASV',"usar ftp pasivo?"); 
define('C_FTP_PORT',"puerto FTP");
define('C_FTP_DEL',"borrar archivos en servidor FTP"); 
define('C_EMAIL_USE',"usar email?");    
define('C_EMAIL',"dirección de email");    
define('C_STYLESHEET',"Apariencia");       
define('C_DATE',"Formato de fecha");        
define('C_DEL_TIME',"borrar backups locales después de x días"); 
define('C_DEL_NUMBER',"archivar máximo x archivos por base de datos"); 
define('C_TIMELIMIT',"php timelimit");      
define('C_IMPORT_ERROR',"mostrar errores de importación?"); 
define('C_NO_LOGIN',"desactivar función de identificación?");  
define('C_LOGIN',"autentificación HTTP?");    
define('C_DIR_BACKUP',"habilitar directorio de backup?");    
define('C_DIR_REC',"directorio backup con subdirectorios?");    
define('C_CONFIRM',"nivel de confirmación");    
define('C_CONFIRM_1',"vaciar, borrar, importar");    
define('C_CONFIRM_2',"... todos");     
define('C_CONFIRM_3',"... TODOS");  
define('C_CONFIRM_4',"no confirmar nada");
define('C_BASIC_VAL',"Configuración básica");   
define('C_EXT_VAL',"Configuración extendida");  
define('PMBP_C_SYSTEM_VAL',"Variables de sistema");  
define('PMBP_C_SYS_WARNING',"Estas variables de sistema son controladas por phpMyBackupPro. No las edite a menos que este seguro de lo que hace!");    
define('C_TITLE_SQL',"Datos SQL");  
define('C_TITLE_FTP',"Backup por FTP");  
define('C_TITLE_EMAIL',"Backup por email");  
define('C_TITLE_STYLE',"Estilo de phpMyBackupPro");    
define('C_TITLE_DELETE',"Borrado automático de archivos de backup"); 
define('C_TITLE_CONFIG',"Más items de configuración");    
define('C_WRONG_TYPE',"no es correcto!");    
define('C_WRONG_SQL',"datos MySQL no son correctos!");  
define('C_WRONG_DB',"nombre de base de datos MySQL no es correcto!"); 
define('C_WRONG_FTP',"datos FTP no son correctos!");   
define('C_OPEN',"No se puede abrir");     
define('C_WRITE',"No se puede escribir en");   
define('C_SAVED',"Datos correctamente guardados");    
define('C_WRITEABLE',"no se puede escribir");   
define('C_SAVE',"Grabar datos");    

/*import.php*/   
define('IM_ERROR',"ocurrieron %d error(es). Puedes usar 'Vaciar Base de Datos' para estar seguro si la base de datos no contiene ninguna tabla.");     
define('IM_SUCCESS',"Correctamente importado"); 
define('IM_TABLES',"tablas y");   
define('IM_ROWS',"registros");    
define('B_EMPTIED_ALL',"Todas las bases de datos fueron vaciadas correctamente");   
define('B_EMPTIED',"La base de datos fué vaciada correctamente");   
define('B_DELETED',"El archivo fué borrado correctamente");    
define('B_DELETED_ALL',"Todos los archivos fueron borrados correctamente");   
define('B_NO_FILES',"Actualmente no hay archivos de backups");    
define('B_DELETE_ALL_2',"borrar TODOS los backups");      
define('B_IMPORT_ALL',"importar TODOS los backups");    
define('B_EMPTY_ALL',"vaciar TODAS las bases de datos");    
define('B_EMPTY_DB',"vaciar base de datos");    
define('B_DELETE_ALL',"borrar todos los backups");    
define('B_INFO',"info");      
define('B_VIEW',"ver");    
define('B_DOWNLOAD',"descargar");  
define('B_IMPORT',"importar"); 
define('B_DELETE',"borrar");    
define('B_CONF_EMPTY_DB',"Realmente deseas vaciar la base de datos?"); 
define('B_CONF_DEL_ALL',"Realmente deseas borrar todos los backups de esta base de datos?");   
define('B_CONF_IMP',"Realmente deseas importar este backup?");  
define('B_CONF_DEL',"Realmente deseas borrar este backup?");     
define('B_CONF_EMPT_ALL',"Realmente deseas vaciar TODAS las bases de datos?");   
define('B_CONF_IMP_ALL',"Realmente deseas importar TODOS los últimos backups?");  
define('B_CONF_DEL_ALL_2',"Realmente deseas borrar todos los backups?");   
define('B_LAST_BACKUP',"Ultimo backup realizado");       
define('B_SIZE_SUM',"Tamaño total de todos los backups");      

/*backup.php*/       
define('EX_SAVED',"Archivo correctamente guardado en");   
define('EX_NO_DB',"Sin base de datos seleccionada");
define('EX_EXPORT',"Backup");    
define('EX_NOT_SAVED',"No puedo guardar la base de datos %s");    
define('EX_DIRS',"Seleccione directorio para backup para servidor FTP"); 
define('EX_DIRS_MAN',"Indica más directorios relativos al directorio phpMyBackupPro.<br>Separados con '|'");  
define('PMBP_EX_NO_AVAILABLE',"Base de datos %s no está disponible");  
define('PMBP_EXS_UPDATE_DIRS',"Actualizar lista de directorio");       
define('PMBP_EX_NO_ARGV',"Utilizar por ejemplo:\n$ php backup.php db1,db2,db3    
Para más funciones por favor lea 'SHELL_MODE.txt' en el directorio 'documentation' ");  

/*scheduled.php*/    
define('EXS_PERIOD',"Seleccionar frecuencia de backup");        
define('EXS_PATH',"Seleccionar directorio donde el archivo php podría ser ubicado"); 
define('EXS_BACK',"atrás");    
define('PMBP_EXS_ALWAYS',"A cada llamada");    
define('EXS_HOUR',"hora");  
define('EXS_HOURS',"horas");     
define('EXS_DAY',"día");  
define('EXS_DAYS',"días");   
define('EXS_WEEK',"semana");   
define('EXS_WEEKS',"semanas");   
define('EXS_MONTH',"mes");      
define('EXS_SHOW',"Mostrar script");    
define('PMBP_EXS_INCL',"Incluir este script en el archivo php (%s) para realizar el backup"); 
define('PMBP_EXS_SAVE',"o guarde este script en un nuevo archivo (podría sobre escribir un archivo existente!)");   

/*file_info.php*/     
define('INF_INFO',"Información");    
define('INF_DATE',"Fecha"); 
define('INF_DB',"Base de datos");   
define('INF_SIZE',"Tamaño de backup");  
define('INF_COMP',"Está comprimido");   
define('INF_DROP',"Contiene 'drop table'"); 
define('INF_TABLES',"Contiene tablas");    
define('INF_DATA',"Contiene datos");      
define('INF_COMMENT',"Comentarios");      
define('INF_NO_FILE',"No hay archivo seleccionado");   

/*db_status.php*/      
define('DB_NAME',"nombre de la base de datos");   
define('DB_NUM_TABLES',"número de tablas");
define('DB_NUM_ROWS',"número de registros");  
define('DB_SIZE',"tamaño");     
define('DB_DIFF',"Tamaños pueden diferir del tamaño del archivo de backup!");    
define('DB_NO_DB',"Sin bases de datos disponibles");   
define('DB_TABLES',"información de tablas");         
define('DB_TAB_TITLE',"tablas en base de datos ");     
define('DB_TAB_NAME',"nombre de la tabla");   
define('DB_TAB_COLS',"número de los campos");   


/*sql_query.php*/    
define('SQ_ERROR',"Ocurrió un error en el número de consulta");    
define('SQ_SUCCESS',"Correctamente ejecutada");      
define('SQ_RESULT',"Resultado de la consulta");   
define('SQ_AFFECTED',"Número de registros afectados");   
define('SQ_WARNING',"Atención: Esta página es solo para generar y enviar consultas simples a las bases de datos.<br>************ Tener especial cuidado para no dañar su base de datos!");  
define('SQ_SELECT_DB',"Seleccione base de datos"); 
define('SQ_INSERT',"Indica tu consulta SQL aqui");    
define('SQ_FILE',"Subir archivo SQL");  
define('SQ_SEND',"Ejecutar");   

/*login.php*/    
define('LI_MSG',"Por favor identifíquese (use su nombre de usuario y contraseña de MySQL)");  
define('LI_USER',"nombre de usuario");   
define('LI_PASSWD',"contraseña");   
define('LI_LOGIN',"Identificarse");  
define('LI_LOGED_OUT',"Comprometer salida segura!");  
define('LI_NOT_LOGED_OUT',"No comprometer salida segura!<br>Para salir con seguridad ingrese una contraseña ERRONEA");  

/*big_import.php*/
define('BI_IMPORTING_FILE',"Importando Archivo");
define('BI_INTO_DB',"Dentro de la Base de Datos");
define('BI_SESSION_NO',"Número de Sesión");
define('BI_STARTING_LINE',"Comienzo en linea");
define('BI_STOPPING_LINE',"Finalizado en linea");
define('BI_QUERY_NO',"Cantidad de consultas realizadas");
define('BI_BYTE_NO',"Número de bytes procesados hasta ahora");
define('BI_DURATION',"Duración de la última sesión");
define('BI_THIS_LAST',"Esta sesión/total");
define('BI_END',"Alcanzado el final del archivo, la importación aparenta BIEN realizada");
define('BI_RESTART',"Reiniciar importación de archivo ");
define('BI_SCRIPT_RUNNING',"El script todavía se está ejecutando!<br>Por Favor espere hasta el final del su ejecución");
define('BI_CONTINUE',"Continua desde la línea");
define('BI_ENABLE_JS',"Activar JavaScript para continuar automaticamente");
define('BI_BROKEN_ZIP',"El archivo ZIP semeja estar roto");
define('BI_WRONG_FILE',"Parado en linea %s.<br>La actual consulta incluye mas que %s lineas. Esto ocurre cuando el backup fue creado
por alguna herramient que no interpreta los saltos de linea en cada consulta, o si el archivo de backup contiene muchos INSERTS.");
?>

