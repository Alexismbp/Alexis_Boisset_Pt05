## **Porra de Futbol - Gestió de Resultats**.

### 1. Explicació del Projecte

Aquest projecte és una aplicació de **porra de futbol** en què els usuaris poden predir els resultats dels partits de futbol de diverses lligues (LaLiga, Premier League, Ligue 1). A més, inclou funcionalitats d'autenticació d'usuaris, gestió de partits, i sistema de feedback visual per veure si l'usuari ha encertat la seva predicció.

### 2. Estructura de carpetes i arxius

```plaintext
ALEXIS_BOISSET_PT04/
├── controller
│   ├── config.php                 # Configuració general del projecte.
│   ├── delete.php                 # Controla la eliminació d'elements (ex: partits o prediccions).
│   ├── guardar_prediccio.php      # Guarda la predicció d'un usuari.
│   ├── login.controller.php       # Processa el login d'usuaris.
│   ├── logout.php                 # Gestiona el logout i tanca la sessió d'un usuari.
│   ├── register.controller.php    # Controla el registre de nous usuaris.
│   ├── save_partit.php            # Guarda un nou partit a la base de dades.
│
├── model
│   ├── db_conn.php                # Connexió a la base de dades mitjançant PDO.
│   ├── porra.php                  # Funcions per gestionar les prediccions dels partits.
│   ├── Pt04_Alexis_Boisset.sql    # Estructura i dades inicials de la base de dades.
│   ├── user_model.php             # Funcions de gestió d'usuaris (registre, verificació).
│
├── scripts
│   ├── lligaequip.js              # Script per mostrar equips segons la lliga seleccionada.
│
├── view
│   ├── styles
│   │   ├── styles_crear.css       # Estils per la pàgina de creació de registres.
│   │   ├── styles_eliminar.css    # Estils per la pàgina d'eliminació.
│   │   ├── styles_llistar.css     # Estils per la pàgina de llistat d'elements.
│   │   ├── styles_login.css       # Estils per la pàgina de login.
│   │   ├── styles_register.css    # Estils per la pàgina de registre.
│   │   ├── styles.css             # Estils generals per l'aplicació.
│   │
│   ├── change_pswd.php            # Pàgina per canviar la contrasenya de l'usuari.
│   ├── crear_partit.php           # Form per crear nous partits (amb selecció de lliga/equip).
│   ├── eliminar.php               # Interfície per eliminar elements.
│   ├── index.view.php            # Pàgina principal que mostra els partits i prediccions.
│   ├── login.view.php            # Formulari de login.
│   ├── register.view.php          # Formulari de registre.
│
├── .gitignore                     # Arxius i carpetes a ignorar en Git.
└── index.php                      # Punt d'entrada principal de l'aplicació.
```


### 3. Funcionalitats Principals

- **Autenticació d'usuaris**: Permet als usuaris crear comptes i iniciar sessió. Només els usuaris autenticats poden fer prediccions i gestionar alguns elements de la plataforma.
- **Predicció de partits**: Els usuaris poden fer prediccions de partits futurs i veure si han encertat una vegada el partit s'ha jugat.
- **Filtrat dinàmic de selecció d'equip**: Quan es registra, l'usuari pot seleccionar un equip favorit, el qual queda registrat al seu perfil. A més, pot veure només els partits del seu equip si així ho desitja.
- **Control de sessió i cookies**: Manté les sessions actives durant 40 minuts i guarda preferències com el nombre de partits per pàgina o la lliga seleccionada.
- **Gestió de partits**: Permet afegir, modificar i eliminar partits (per usuaris amb permisos).
- **Lifetime màxim de sessions personalitzat**: S'ha creat especialment per aquest projecte un arxiu que substitueix la funció session.gc_maxlifetime per una que sí que funciona. Molt robusta y amb rutes relatives que funcionen en qualsevol entorn on s'executi (A.K.A.: **Aplicació portàtil**). Si algú em trenca el funcionament d'aquest arxiu, ploraria.

### 4. Base de Dades

La base de dades conté les següents taules principals per gestionar els equips, lligues, partits i usuaris:

- **Taula `equips`**: Inclou informació sobre els equips de diferents lligues, amb camps com `id`, `nom` (nom de l'equip) i `lliga_id` (identificador de la lliga a la qual pertany l'equip).

- **Taula `lligues`**: Emmagatzema les lligues en què participen els equips, amb els camps `id` i `nom` (nom de la lliga).

- **Taula `partits`**: Conté els partits que es disputen entre equips. Els camps inclouen `equip_local_id` i `equip_visitant_id` per identificar els equips participants, `data` per la data del partit, `gols_local` i `gols_visitant` per als resultats, `jugat` per indicar si el partit s'ha jugat, i `liga_id` per associar-lo a la lliga corresponent.

- **Taula `usuaris`**: Emmagatzema els usuaris que poden accedir al sistema i interactuar amb ell. Inclou camps com `id`, `nom_usuari`, `contrasenya` i altres dades de perfil.

### 4.1  Usuaris Registrats

Aquests són els usuaris que venen registrats a la base de dades per defecte:

| Nom d'Usuari         | Contrasenya       | Equip Favorit             | Lliga                    |
|----------------------|-------------------|---------------------------|--------------------------|
| alexis@gmail.com     | Admin123          | OGC Nice                  | Ligue 1                  |
| xavi@gmail.com       | Admin123          | Girona FC                 | La Liga                  |
| jpedrerol@gmail.com  | Admin123          | Crystal Palace            | Premier League           | 

### 5. Tecnologies Utilitzades
   - Llista de les tecnologies emprades, com:
     - **Backend**: PHP amb PDO per a la gestió de la base de dades.
     - **Frontend**: HTML, CSS, JavaScript (per a la càrrega dinàmica d'equips).
     - **Base de Dades**: MySQL, amb una estructura de taules per usuaris, partits, lligues i equips.
     - **Gestió d'Errors**: Try-catch amb `Throwable` per capturar i mostrar errors d'execució.

### 6. Instruccions per l'Usuari Nou

1. **Registrar-se**: Si és la primera vegada que accedeixes, ves a la pàgina de registre (`register.view.php`) i crea un compte amb les teves dades. Aquí pots seleccionar la teva lliga preferida i el teu equip favorit.
   
2. **Iniciar Sessió**: Un cop registrat, pots iniciar sessió a la pàgina de login (`login.view.php`) amb les teves credencials.

3. **Fer Prediccions**: A la pàgina principal (`index.view.php`), podràs veure una llista de partits. Si el partit encara no s'ha jugat, pots introduir una predicció pel resultat.

4. **Revisar els Resultats**: Quan un partit es jugui, podràs veure el resultat real comparat amb la teva predicció. Els gols apareixeran en verd si has encertat i en vermell si no.

5. **Gestionar Preferències**: Pots escollir quants partits veure per pàgina des d'un menú desplegable. Aquest valor es guardarà en una cookie per recordar la teva elecció la propera vegada que visitis la pàgina.

6. **Tancar Sessió**: Quan hagis acabat, recorda tancar la sessió des de l'opció de logout per garantir la seguretat del teu compte.


### 7. Instal·lació
   - Passos per instal·lar el projecte en local:
     - **1**: Clonar el repositori al teu directori desitjat.
     - **2**: Crear/Importar la base de dades amb l'estructura proporcionada (P.E.: ***phpMyAdmin***).
     - **3**: Accedir al projecte des d'un servidor local (P.E.: ***XAMPP***).



   ### 8. Documentació del Codi

Aquest projecte inclou funcions per gestionar una porra de futbol amb autenticació d'usuaris, prediccions, paginació de partits i gestió de preferències d'equip. A continuació, es descriuen algunes de les funcions més rellevants:

- **connectarBaseDades()**: Estableix una connexió segura amb la base de dades utilitzant PDO per permetre operacions com insercions, actualitzacions i consultes de dades.

- **registrarUsuari()**: Processa el registre de nous usuaris, validant les dades i associant l'equip favorit i la lliga seleccionats durant el registre.

- **autenticarUsuari()**: Verifica les credencials de l'usuari per permetre l'accés i crea una sessió amb la informació de l'usuari autenticat, incloent l'equip favorit.

- **mostrarPartitsPaginats()**: Gestiona la visualització paginada dels partits. Diferencia entre usuaris autenticats (que només veuen partits del seu equip favorit) i no autenticats (que veuen tots els partits).

- **afegirPartit()**: Permet crear un nou partit seleccionant equips específics per lliga, actualitzant automàticament el llistat d'equips segons la lliga escollida.

- **actualitzarEquipsPerLliga()**: Carrega i mostra dinàmicament els equips disponibles segons la lliga seleccionada al formulari, facilitant la selecció precisa per a cada lliga.

- **predirResultat()**: Permet als usuaris autenticats predir el resultat d’un partit abans que es jugui. Compara la predicció amb el resultat real i mostra visualment si l'usuari ha encertat o no. 

Aquestes funcions proporcionen una estructura completa i funcional per a la gestió de la porra, amb una experiència d'usuari intuïtiva i dinàmica.

*Nota: El nom d'aquestes funcions pot variar en l'idioma o l'ordre del nom de la funció. Només s'ha simplificat els noms en aquest arxiu per lectura més senzilla.*


### 9. Problemes Coneguts i Limitacions
   - L'autor us asegura de tot cor que coneix la limitació **actual** per afegir prediccions de partit a la base de dades.
   - L'autor es conscient que en comptes de afegir un enllaç que redirigeixi a una pàgina per crear un partit o per eliminar-lo, es podria fer a la mateixa pàgina principal. L'autor reclama que no té temps per fer-ho.

### 10. Crèdits
   - Autor del projecte, Alexis Boisset.

### 11. Llicència
   - Under MIT license.