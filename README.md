# **Porra de Futbol - Gestió de Resultats**

## -2. Changelog

**No perdis temps Xavi, et deixo el changelog desde l'última vegada que vas veure el projecte:**

- He creat una API Rest per demanar partits, crear partits, actualitzar partits y eliminar partits. Aquesta API necessita una API KEY que es genera al perfil de cada usuari, abaix de tot de la view podrás veure el botó per generar aquesta key. Cada vegada que generas una nova API KEY es guarda a una tabla de la base de dades relacionada amb l'usuari. Aquesta API KEY es necessaria per fer qualsevol petició a la API Rest.
- La API Rest a la que faig sol·licitud la pots trobar al menu dropdown a "Equips", tota la informació que hi ha allá reflexada ve per API, inclosa la dels jugadors de cada equip amb les seves imatges i posicions.
- Després de tot el tema API: QR. El flux que ha de fer l'usuari es el següent:
  - Crear un partit (si no té) amb article (titol o cos).
  - Una vegada creat anar a "veure" aquest partit (botó veure a cada targeta de partit).
  - A baix de tot de la view hi ha un botó "compartir".
  - Selecciona els camps que vols compartir, després clica compartir un altre cop.
  - Decideix si vols escanejar el QR, compartir-lo com imatge o clicar l'enllaç o el que vulguis.
- Fet aixó l'article ja estará a la taula de "shared_articles" així que qualsevol persona loguejada pot entrar a la vistaAjax (Partits compartits al menu dropdown) y veure aquest partit y agafar-lo com a plantilla
- **OJO** que la meva pàgina filtra els partits per lligues, si dones d'alta com a article teu un article d'una lliga que no és la teva no el veurás a la teva pàgina d'inici a menys que al perfil canvïis la lliga a la que perteneixes a la lliga del article que has donat d'alta. (No sé si lo he explicado bien pero que tengas cuidado que la liga tiene que coincidir si no parece que se está bugueando la APP pero es así) Com a posible millora podría afegir un filtratge a la vistaAjax (Partits compartits) per lligues però no ho he fet per falta de temps.

Dit això, pasem a les coses curioses o justificacions

## -1. Justificacions i curiositats

El controller que fa les sol·licituds a l'API de Football `FootballApi.php` té una funcionalitat que és senzillament el guardar "caché" (memoria cau). Es necessari ja que -a més de ser més ràpid- la API de Football té un límit de peticions diàries (100), així cada vegada que tiro enrere o algo així no fa una nova petició a la API de Football. Aquesta caché es guarda al mateix directori que l'arxiu PHP i és valid per 3600 segons (1 hora).

El tema de que els articles s'han de poder duplicar pero després no poden haber duplicats: La meva solució és que a la taula de `shared_articles` es poden duplicar els articles, o sigui, puc compartir el mateix article 1000 vegades si vull i amb les mateixes condicions (mostrar només titol, cos, o les dos opcions). Però a l'hora de donar d'alta l'article a la taula d'`articles` un usuari no pot tenir el mateix article duplicat. Així arreglem la singularitat de l'enunciat.

**Justificació i valoració fetch manual vistaAjax:** El botó permet que l'usuari controli quan es realitza el fetch, evitant peticions innecessàries a la base de dades i fer un ús excessiu dels recursos. També millora l'experiència d'usuari, ja que només s'actualitza la informació quan és realment necessari, imagina que estás 10 minuts buscant un article per donar d'alta i quan el trobes de sobte s'actualitza la pàgina i ara tens que tornar a trobar-lo. (WEBSOCKETS SERIA LA MILLOR SOLUCIÓ, però a día d'avui no se com fer-ho, se que és i que existeix pero no se com implementar-ho).

**Justificació i valoració lectura API Rest:** El que he fet es que si la petició que s'està fent no es troba a la memòria cau llavors s'ha de fer petició a l'API, i ja està no s'acaba el món, els DNS funcionen així.

**Justificació i valoració QR:** El que he fet amb el QR es simplement guardar a dins l'id (token) de l'article compartit a la taula de `shared_articles`. Cada vegada que s'escaneja el QR es fa una petició a la base de dades per obtenir l'article amb aquest token.

**Configuracions i routing per API:** Sincerament no he fet res a l'htaccess, ja el tenia configurat de la pràctica pasada per al meu sistema de routing. El router si que l'he modificat i he afegit arrays bidimensionals pels metodes PUT y DELETE. Després només he hagut d'afegir les rutes a l'index.php i ja està.

**Aquí acaben les justificacions i curiositats, lo que queda de README es el mateix que la pràctica anterior, qualsevol cosa pots mirar el codi que está en general documentat.**

## 0. Índex

- [**Porra de Futbol - Gestió de Resultats**](#porra-de-futbol---gestió-de-resultats)
  - [-2. Changelog](#-2-changelog)
  - [-1. Justificacions i curiositats](#-1-justificacions-i-curiositats)
  - [0. Índex](#0-índex)
  - [1. Explicació del Projecte (Deprecated)](#1-explicació-del-projecte-deprecated)
  - [2. Estructura del Projecte](#2-estructura-del-projecte)
  - [3. Funcionalitats Principals](#3-funcionalitats-principals)
  - [4. Base de Dades](#4-base-de-dades)
  - [5. Usuaris Predefinits](#5-usuaris-predefinits)
  - [6. Tecnologies Utilitzades](#6-tecnologies-utilitzades)
  - [7. Instruccions per a l'Usuari Nou](#7-instruccions-per-a-lusuari-nou)
  - [8. Instal·lació](#8-installació)
  - [9. Documentació del Codi](#9-documentació-del-codi)
  - [10. Problemes Coneguts i Limitacions](#10-problemes-coneguts-i-limitacions)
  - [11. Crèdits](#11-crèdits)
  - [12. Llicència](#12-llicència)
  - [13. Detalls Tècnics Addicionals](#13-detalls-tècnics-addicionals)
    - [Router.php](#routerphp)
    - [SocialAuthController.php](#socialauthcontrollerphp)
    - [SessionHelper.php](#sessionhelperphp)
    - [database.model.php](#databasemodelphp)
  - [Justificació de l'eliminació en cascada d'articles](#justificació-de-leliminació-en-cascada-darticles)
    - [change-password.controller.php](#change-passwordcontrollerphp)
    - [form.controller.php](#formcontrollerphp)
    - [match.controller.php](#matchcontrollerphp)
    - [lligaequip.js](#lligaequipjs)
    - [styles.css](#stylescss)
    - [index.php](#indexphp)
    - [.gitignore](#gitignore)
    - [Configuració de Seguretat](#configuració-de-seguretat)
      - [Redirecció i Routing](#redirecció-i-routing)
      - [Protecció del Servidor](#protecció-del-servidor)
      - [Prevenció d'Accés](#prevenció-daccés)
      - [HTTPS i WWW (Comentat en Desenvolupament)](#https-i-www-comentat-en-desenvolupament)

## 1. Explicació del Projecte (Deprecated)

Aquest projecte és una aplicació de **porra de futbol** que permet als usuaris predir els resultats dels partits de futbol de diverses lligues (LaLiga, Premier League, Ligue 1). Inclou funcionalitats d'autenticació d'usuaris, gestió de partits i un sistema de feedback visual per veure si l'usuari ha encertat la seva predicció.

També inclou una interfície d'usuari per afegir, editar i eliminar articles sobre partits i donar la seva opinió sobre els resultats. Els administradors poden gestionar els partits i les prediccions dels usuaris des de l'aplicació.

## 2. Estructura del Projecte

```plaintext
ALEXIS_BOISSET_PT05/
├── controllers
│   ├── styles.controller.php              # Controlador principal de l'aplicació.
│   ├── auth
│   │   ├── login.controller.php         # Processa el login d'usuaris.
│   │   ├── logout.controller.php        # Gestiona el logout i tanca la sessió.
│   │   ├── register.controller.php      # Controla el registre de nous usuaris.
│   │   ├── preferences.controller.php   # Gestiona les preferències dels usuaris.
│   │   └──SocialAuthController.php     # Gestiona l'autenticació social amb OAuth i HybridAuth.
│   ├── match
│   │   ├── match.controller.php         # Controla les operacions relacionades amb els partits.
│   │   └── prediction.controller.php    # Gestiona les prediccions dels usuaris.
│   └── utils
│       ├── cookie.controller.php        # Gestiona les cookies de l'aplicació.
│       ├── SessionHelper.php            # Funcions auxiliars per a la gestió de sessions.
│       ├── form.controller.php          # Validació i processament de formularis.
│       ├── RecaptchaController.php      # Gestiona la verificació de reCAPTCHA.
│       ├── validation.controller.php    # Funcions de validació de dades.
│       └── search.controller.php        # Gestiona les cerques dins l'aplicació.
│
├── models
│   ├── database
│   │   ├── database.model.php           # Connexió a la base de dades mitjançant PDO.
│   │   ├── Pt05_Alexis_Boisset.sql      # Esquema de la base de dades.
│   ├── user
│   │   ├── user.model.php               # Funcions de gestió d'usuaris.
│   ├── utils
│   │   └── porra.model.php              # Funcions per gestionar els partits.
│   └── env.php
│
├── scripts
│   ├── cleanup-tokens.php               # Script per netejar tokens obsolets.
│   ├── index.js                         # Script principal per gestionar la lògica del client.
│   ├── lligaequip.js                    # Script per mostrar equips segons la lliga seleccionada.
│   └── prediction.js                    # Script per gestionar les prediccions en temps real.
│
├── views
│   ├── admin
│   │   ├── manage-users.view.php         # Pàgina per gestionar els usuaris.
│   │   └── styles_manage-users.css       # Estils per a la pàgina de gestió d'usuaris.
│   ├── auth
│   │   ├── change
│   │   │   ├── change-password.view.php   # Formulari per canviar la contrasenya.
│   │   │   └──styles_change.css          # Estils per a la pàgina de canvi de contrasenya.
│   │   ├── forgot
│   │   │   ├── forgot-password.view.php   # Formulari per restablir la contrasenya.
│   │   │   └──styles_forgot.css          # Estils per a la pàgina de restabliment de contrasenya.
│   │   ├── login
│   │   │   ├── login.view.php             # Formulari de login.
│   │   │   └── styles_login.css           # Estils per a la pàgina de login.
│   │   ├── merge
│   │   │   ├── merge-accounts.view.php    # Pàgina per fusionar comptes d'usuari.
│   │   │   └── styles_merge.css           # Estils per a la pàgina de fusió de comptes.
│   │   ├── preferences
│   │   │   ├── preferences.view.php       # Formulari per configurar preferències.
│   │   │   └── styles_preferences.css     # Estils per a la pàgina de preferències.
│   │   ├── profile
│   │   │   ├── profile.view.php           # Pàgina de perfil de l'usuari.
│   │   │   └── styles_profile.css         # Estils per a la pàgina de perfil.
│   │   ├── register
│   │   │   ├── register.view.php          # Formulari de registre.
│   │   │   └── styles_register.css        # Estils per a la pàgina de registre.
│   │   └── reset
│   │       ├── reset-password.view.php    # Formulari per restablir la contrasenya.
│   │       └──styles_reset.css           # Estils per a la pàgina de restabliment de contrasenya.
│   ├── components
│   │   ├── admin-actions.component.php           # Accions d'administrador.
│   │   ├── footer.component.php                  # Peu de pàgina comú de l'aplicació.
│   │   ├── header.component.php                  # Capçalera comuna de l'aplicació.
│   │   ├── league-selector.component.php         # Selector de lligues.
│   │   ├── match-actions.component.php           # Accions relacionades amb els partits.
│   │   ├── matches-list.component.php            # Llista de partits.
│   │   ├── matches-per-page.component.php        # Selector de nombre de partits per pàgina.
│   │   └── pagination.component.php              # Paginació de llistes.
│   ├── crud
│   │   ├── create
│   │   │   ├── admin-create.view.php             # Formulari per crear nous administradors.
│   │   │   ├── match-create.view.php             # Formulari per crear nous partits.
│   │   │   ├── styles_crear.css                  # Estils per a la pàgina de creació.
│   │   ├── delete
│   │   │   ├── delete.view.php                   # Confirmació per eliminar un element.
│   │   │   ├── styles_eliminar.css               # Estils per a la pàgina d'eliminació.
│   │   ├── edit
│   │   │   ├── match-edit.view.php               # Formulari per editar partits existents.
│   │   └── view
│   │       ├── match-view.view.php               # Vista detallada d'un partit.
│   │       └── styles_match-view.css             # Estils per a la pàgina de vista de partits.
│   ├── errors/
│   │   ├── 404.view.php                        # Pàgina d'error 404.
│   ├── layouts/
│   │   ├── feedback.view.php                    # Missatges de feedback per a l'usuari.
│   └── styles/
│       ├── index.view.php                       # Pàgina principal de l'aplicació.
│       └──styles.css                           # Estils per a la pàgina principal.
│
│
├── .gitignore                           # Arxius i carpetes a ignorar en Git.
└── index.php                            # Punt d'entrada principal de l'aplicació.
```

## 3. Funcionalitats Principals

- **Autenticació d'usuaris**: Permet als usuaris registrar-se i iniciar sessió per accedir a les funcionalitats.
- **Selecció dinàmica d'equips**: Durant el registre, l'usuari selecciona la seva lliga i equip favorit.
- **Gestió de partits**: Administradors poden afegir, editar i eliminar partits des de l'aplicació.
- **Control de sessió i preferències**: Les sessions tenen una durada limitada i es guarden preferències de l'usuari.
- **Feedback visual**: Es mostra si l'usuari ha encertat la predicció amb indicadors visuals.

## 4. Base de Dades

La base de dades inclou les taules principals següents:

- **`equips`**: Informació dels equips, incloent nom i lliga a la qual pertanyen.
- **`lligues`**: Llistat de lligues disponibles amb els seus respectius identificadors.
- **`partits`**: Registre de partits amb equips participants, data i resultats.
- **`usuaris`**: Informació dels usuaris, incloent credencials i preferències.
- **`prediccions`**: Prediccions realitzades pels usuaris per als diferents partits.

## 5. Usuaris Predefinits

A continuació es mostren els usuaris predefinits:

| Nom d'Usuari            | Contrasenya | Equip Favorit  | Lliga          |
| ----------------------- | ----------- | -------------- | -------------- |
| admin@alexisboisset.cat | Admin123!   | -              | -              |
| alexis@gmail.com        | Admin123    | OGC Nice       | Ligue 1        |
| xavi@gmail.com          | Admin123    | Girona FC      | LaLiga         |
| pedrerol@gmail.com      | Admin123    | Crystal Palace | Premier League |

## 6. Tecnologies Utilitzades

- **Backend**: PHP amb PDO per a la interacció amb la base de dades.
- **Frontend**: HTML, CSS i JavaScript per a la interfície d'usuari.
- **Base de Dades**: MySQL per emmagatzemar dades dels usuaris i partits.
- **Gestió d'Errors**: Maneig d'excepcions amb `try-catch` per assegurar l'estabilitat.

## 7. Instruccions per a l'Usuari Nou

1. **Registrar-se**: Accedeix a `register.view.php` per crear un nou compte.
2. **Iniciar Sessió**: Utilitza `login.view.php` per iniciar sessió amb les teves credencials.
3. **Configurar Preferències**: Selecciona la teva lliga i equip favorit després d'iniciar sessió (Si loguejas per Oauth).
4. **Crear article**: A `index.view.php`, ves al header, clica a la teva icona i podrás insertar articles sobre partits del teu equip favorit juntament amb el resultat del partit.
5. **Modificar Perfil**: Accedeix a `profile.view.php` per actualitzar les teves dades.
6. **Tancar Sessió**: Fes logout per assegurar la confidencialitat de la teva sessió.

## 8. Instal·lació

Per instal·lar el projecte:

1. **Clona el repositori** al teu ordinador.
2. **Importa la base de dades** proporcionada utilitzant una eina com phpMyAdmin (es troba a `/models/database/Pt05_Alexis_Boisset.sql`).
3. **Configura la connexió** a la base de dades en el fitxer de configuració.
4. **Executa el servidor local** amb XAMPP o similar i accedeix al projecte.

## 9. Documentació del Codi

- **`connectarBaseDades()`**: Inicia una connexió segura amb la base de dades.
- **`registrarUsuari()`**: Gestiona el procés de registre d'un nou usuari.
- **`autenticarUsuari()`**: Verifica les credencials i inicia la sessió de l'usuari.
- **`mostrarPartitsPaginats()`**: Mostra els partits amb opció de paginació.
- **`afegirPartit()`**: Permet afegir nous partits a la base de dades.

## 10. Problemes Coneguts i Limitacions

- **Funcions no utilitzades**: Alguns arxius están més refactoritzats que altres. Aixó es pot veure en arxius on es crida la classe SessionHelper que conté funcions utils per tot el projecte, peró per manca de temps només s'ha implementat en arxius més recents o importants. En un futur es podrá implementar en tots els arxius.

## 11. Crèdits

- Autor del projecte: Alexis Boisset.

## 12. Llicència

- Aquest projecte està sota la llicència MIT.

## 13. Detalls Tècnics Addicionals

### Router.php

El fitxer `Router.php` és responsable de gestionar les rutes de l'aplicació. Utilitza una estructura de matriu per emmagatzemar les rutes GET i POST. Quan es fa una sol·licitud, el router comprova si la ruta sol·licitada coincideix amb alguna de les rutes definides i executa el controlador corresponent. També gestiona rutes per a fitxers estàtics com imatges i fulls d'estil.

### SocialAuthController.php

La classe `SocialAuthController.php` gestiona l'autenticació social mitjançant OAuth. Aquesta classe permet als usuaris iniciar sessió amb proveïdors com Google i GitHub. Inclou mètodes per redirigir els usuaris als proveïdors d'autenticació, gestionar les respostes de callback i processar les dades dels usuaris autenticats. També maneja la fusió de comptes si un usuari ja existeix amb el mateix correu electrònic.

### SessionHelper.php

El fitxer `SessionHelper.php` conté funcions auxiliars per a la gestió de sessions. Inclou mètodes per iniciar i destruir sessions, així com per establir i obtenir dades de sessió. També gestiona la verificació de captcha i el control de l'activitat de la sessió per evitar inactivitat prolongada.

### database.model.php

El fitxer `database.model.php` gestiona la connexió a la base de dades mitjançant PDO. Proporciona una connexió segura i reutilitzable a la base de dades MySQL, permetent l'execució de consultes SQL de manera eficient i segura. S'utilitza el patró singleton.

## Justificació de l'eliminació en cascada d'articles

Quan l'administrador elimina un usuari del sistema, tots els articles associats a aquest usuari s'eliminen automàticament. Aquesta decisió de disseny es basa en els següents motius:

1. **Integritat de dades**: Els articles estan estretament vinculats a l'usuari que els ha creat. Mantenir articles sense un autor associat podria crear inconsistències en la base de dades.

2. **Propietat del contingut**: Els articles representen opinions personals i experiències dels usuaris. Quan un usuari és eliminat, el seu contingut personal també hauria de ser eliminat per respectar la seva privacitat.

3. **Simplificació de la gestió**: L'eliminació en cascada simplifica la gestió de la base de dades i evita tenir contingut "orfe" sense un autor assignat.

4. **Coherència del sistema**: Mantenir articles d'usuaris eliminats podria crear confusió en el sistema i dificultar el manteniment a llarg termini.

5. **Protecció de dades**: Compleix amb les bones pràctiques de protecció de dades, assegurant que tota la informació relacionada amb un usuari s'elimina quan aquest és donat de baixa del sistema.

### change-password.controller.php

El fitxer `change-password.controller.php` gestiona el procés de canvi de contrasenya per als usuaris. Si l'usuari és autenticat mitjançant OAuth, permet afegir una nova contrasenya al compte. En canvi, si l'usuari és normal, verifica la contrasenya actual abans d'actualitzar-la amb una de nova. La justificació d'aquest canvi és per que es molt comú que una pàgina permeti el login tant per OAuth com per usuari i contrasenya, he volgut afegir aquesta funcionalitat per aquesta raó i tampoc és massa complicat.

### form.controller.php

El fitxer `form.controller.php` s'encarrega de la validació i processament de formularis. Inclou funcions per validar camps de formulari com noms d'usuari, correus electrònics i contrasenyes. També maneja la validació de fitxers d'imatge per assegurar que compleixen amb els requisits establerts.

### match.controller.php

El fitxer `match.controller.php` controla les operacions relacionades amb els partits. Permet als administradors afegir, editar i eliminar partits, així com gestionar les prediccions dels usuaris. També inclou funcions per obtenir dades dels partits i mostrar-les a la interfície d'usuari.

### lligaequip.js

El fitxer `lligaequip.js` és un script que mostra els equips disponibles segons la lliga seleccionada per l'usuari. Actualitza dinàmicament la llista d'equips en el formulari de registre i preferències, millorant l'experiència d'usuari.

### styles.css

El fitxer `styles.css` conté els estils generals de l'aplicació. Defineix l'aparença i el disseny de les pàgines, incloent colors, tipografies i disposició dels elements. S'utilitza per mantenir una coherència visual en tota l'aplicació.

### index.php

El fitxer `index.php` és el punt d'entrada principal de l'aplicació. Inicia la sessió, carrega les dependències necessàries i gestiona les rutes definides en el `Router.php`. També inclou la lògica per manejar errors i redirigir els usuaris a les pàgines corresponents segons les seves accions.

### .gitignore

El fitxer `.gitignore` especifica els arxius i carpetes que han de ser ignorats pel sistema de control de versions Git. Això inclou fitxers temporals, configuracions locals i altres arxius que no són necessaris per al funcionament de l'aplicació en altres entorns.

### Configuració de Seguretat

El projecte implementa diverses mesures de seguretat a través del fitxer `.htaccess`:

#### Redirecció i Routing

- Utilitza `RewriteEngine` per gestionar les URL netes
- Redirigeix totes les peticions no existents a `index.php`
- Gestiona errors 404 amb una pàgina personalitzada

#### Protecció del Servidor

- Desactiva el llistat de directoris amb `Options -Indexes`
- Oculta la signatura del servidor amb `ServerSignature Off`

#### Prevenció d'Accés

- Bloqueja l'accés a fitxers que comencen amb punt (.)
- Restringeix l'accés a fitxers sensibles com:
  - Backups (.bak)
  - Configuracions (.config, .ini)
  - Logs (.log)
  - Scripts (.sh)
  - Fitxers SQL (.sql)
  - Fitxers temporals (.swp, .swo)
  - .env (si l'hagués anomenat correctament) i altres fitxers de configuració

#### HTTPS i WWW (Comentat en Desenvolupament)

- Inclou regles per forçar HTTPS
- Configuració per redirigir a www
- Aquestes regles estan comentades per facilitar el desenvolupament local
