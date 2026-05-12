# Symfony 7.4 - Mini application de gestion de livres

## Objectif

Créer une mini-application Symfony permettant de gérer des livres avec :

* inscription utilisateur
* connexion utilisateur
* ajout de livres
* affichage des livres
* modification des livres
* suppression des livres
* interface Twig
* style avec Tailwind CSS
* base de données MySQL

---

# 1. Prérequis

Avant de commencer, vérifier que les outils suivants sont installés :

```bash
php -v
composer -V
node -v
yarn -v
```

Rôle :

* `php` : exécuter Symfony
* `composer` : installer les dépendances PHP
* `node` : gérer les outils front
* `yarn` : gérer les dépendances JavaScript/CSS

---

# 2. Créer le projet Symfony

```bash
composer create-project symfony/skeleton symfony-books
cd symfony-books
```

Rôle :

* crée un nouveau projet Symfony minimal
* entre dans le dossier du projet

---

# 3. Installer le pack web Symfony

```bash
composer require webapp
```

Rôle :

* installe les composants classiques d’une application web Symfony :

  * routing
  * controller
  * Twig
  * Doctrine
  * profiler
  * validator
  * assets

---

# 4. Installer les outils de développement

```bash
composer require --dev symfony/maker-bundle
```

Rôle :

* permet d’utiliser les commandes :

  * `make:entity`
  * `make:controller`
  * `make:crud`
  * `make:user`
  * `make:auth`
  * `make:registration-form`

---

# 5. Installer les dépendances principales

## Doctrine

```bash
composer require doctrine orm maker
```

## Twig

```bash
composer require symfony/twig-bundle
```

## Form + Validation

```bash
composer require symfony/form symfony/validator
```

## Sécurité

```bash
composer require symfony/security-bundle
```

## Assets

```bash
composer require symfony/asset
composer require symfony/webpack-encore-bundle
```

---

# 6. Configurer la base de données

Créer le fichier :

```bash
.env.local
```

Ajouter :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/db_books?serverVersion=mariadb-10.4.32&charset=utf8mb4"
```

Rôle :

* configure la connexion entre Symfony et MySQL
* `db_books` est le nom de la base de données

---

# 7. Créer la base de données

```bash
php bin/console doctrine:database:create
```

Rôle :

* crée la base `db_books` dans MySQL

---

# 8. Créer l’utilisateur de l’application

```bash
php bin/console make:user
```

Réponses conseillées :

```txt
Class name: User
Store user data in the database: yes
Unique property: email
Hash/check user passwords: yes
```

Rôle :

* crée l’entité `User`
* prépare la gestion des utilisateurs
* ajoute email, rôles et mot de passe hashé

---

# 9. Créer le système de connexion

```bash
php bin/console make:auth
```

Choisir :

```txt
Login form authenticator
```

Rôle :

* crée le contrôleur de login
* crée le template de connexion
* configure l’authentification Symfony

---

# 10. Créer le formulaire d’inscription

```bash
php bin/console make:registration-form
```

Réponses conseillées :

```txt
Add UniqueEntity validation: yes
Send email verification: no
Authenticate after registration: yes
```

Rôle :

* crée le formulaire d’inscription
* permet de créer un utilisateur depuis `/register`
* hash automatiquement le mot de passe

---

# 11. Créer l’entité Book

```bash
php bin/console make:entity Book
```

Champs à ajouter :

```txt
title        string   180
author       string   120
isbn         string   30    nullable yes
description  text           nullable yes
publishedAt  datetime_immutable nullable yes
available    boolean
createdAt    datetime_immutable
```

Rôle :

* crée l’entité `Book`
* représente la table des livres en base de données

---

# 12. Créer la migration

```bash
php bin/console make:migration
```

Rôle :

* génère un fichier SQL PHP dans `migrations/`
* décrit les changements à appliquer à la base

---

# 13. Exécuter la migration

```bash
php bin/console doctrine:migrations:migrate
```

Rôle :

* crée les tables en base :

  * `user`
  * `book`

---

# 14. Créer le CRUD des livres

```bash
php bin/console make:crud Book
```

Rôle :

* génère automatiquement :

  * `BookController`
  * `BookType`
  * les templates Twig
  * les routes CRUD

Routes générées :

```txt
/book
/book/new
/book/{id}
/book/{id}/edit
/book/{id}
```

---

# 15. Vérifier les routes

```bash
php bin/console debug:router
```

Rôle :

* affiche toutes les routes disponibles
* permet de vérifier `/login`, `/register`, `/book`

---

# 16. Initialiser Yarn

```bash
yarn init -y
```

Rôle :

* crée le fichier `package.json`
* prépare la partie front du projet

---

# 17. Installer Encore, Babel et Tailwind

## Installer Webpack Encore

```bash
yarn add -D @symfony/webpack-encore@^5 webpack@^5 webpack-cli@^6
```

## Installer Babel

```bash
yarn add -D @babel/core @babel/preset-env babel-loader
```

## Installer Tailwind CSS

```bash
yarn add -D tailwindcss@3 postcss autoprefixer
```

Rôle :

* `webpack-encore` : compile les assets
* `babel` : compile le JavaScript moderne
* `tailwindcss` : framework CSS utilitaire
* `postcss` : transforme le CSS
* `autoprefixer` : ajoute les préfixes CSS automatiquement

---

# 18. Initialiser Tailwind

```bash
npx tailwindcss init
```

Rôle :

* crée le fichier `tailwind.config.js`

---

# 19. Configurer Tailwind

Dans `tailwind.config.js` :

```js
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./assets/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Rôle :

* indique à Tailwind où chercher les classes CSS utilisées

---

# 20. Créer le CSS principal

Dans `assets/styles/app.css` :

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Rôle :

* importe les styles Tailwind dans le projet

---

# 21. Importer le CSS dans JavaScript

Dans `assets/app.js` :

```js
import './styles/app.css';
```

Rôle :

* permet à Encore de compiler le CSS Tailwind

---

# 22. Configurer Webpack Encore

Créer ou vérifier `webpack.config.js` :

```js
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enablePostCssLoader()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
```

Rôle :

* configure la compilation CSS/JS vers `public/build`

---

# 23. Charger les assets dans Twig

Dans `templates/base.html.twig`, ajouter dans `<head>` :

```twig
{{ encore_entry_link_tags('app') }}
```

Avant `</body>` :

```twig
{{ encore_entry_script_tags('app') }}
```

Rôle :

* charge le CSS et le JS compilés dans toutes les pages

---

# 24. Ajouter les scripts Yarn

Dans `package.json` :

```json
"scripts": {
  "dev": "encore dev",
  "watch": "encore dev --watch",
  "build": "encore production"
}
```

Rôle :

* `yarn dev` : compile une fois
* `yarn watch` : recompile automatiquement
* `yarn build` : compile pour la production

---

# 25. Compiler les assets

```bash
yarn dev
```

Ou en mode développement :

```bash
yarn watch
```

Rôle :

* génère les fichiers CSS/JS dans `public/build`

---

# 26. Templates Twig stylisés

## Login

```twig
{% extends 'base.html.twig' %}

{% block title %}Connexion{% endblock %}

{% block body %}
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

        <h1 class="text-2xl font-bold text-center mb-6">Connexion</h1>

        {% if error %}
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ error.messageKey|trans(error.messageData, 'security') }}
            </div>
        {% endif %}

        {{ form_start(form, {
            action: path('app_login'),
            method: 'POST',
            attr: { class: 'space-y-4' }
        }) }}

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            {{ form_widget(form.email, {
                attr: {
                    class: 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none',
                    value: last_email,
                    placeholder: 'Votre email'
                }
            }) }}
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe
            </label>
            {{ form_widget(form.password, {
                attr: {
                    class: 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none',
                    placeholder: 'Votre mot de passe'
                }
            }) }}
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Se connecter
        </button>

        {{ form_end(form, { render_rest: false }) }}

        <p class="mt-4 text-center text-sm text-gray-600">
            Pas de compte ?
            <a href="{{ path('app_register') }}"
               class="text-blue-600 hover:underline">
                S'inscrire
            </a>
        </p>

    </div>
</div>
{% endblock %}
```

---

## Register

```twig
{% extends 'base.html.twig' %}

{% block title %}Inscription{% endblock %}

{% block body %}
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

        <h1 class="text-2xl font-bold text-center mb-6">
            Créer un compte
        </h1>

        {{ form_start(form, {
            attr: {
                class: 'space-y-4',
                'data-turbo': 'false'
            }
        }) }}

            {% if form.vars.errors|length %}
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        {% for error in form.vars.errors %}
                            <li>{{ error.message }}</li>
                        {% endfor %}
                    </ul>
                </div>
            {% endif %}

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                {{ form_widget(form.email, {
                    attr: {
                        class: 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none',
                        placeholder: 'Votre adresse email'
                    }
                }) }}
                {{ form_errors(form.email) }}
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                {{ form_widget(form.plainPassword.first, {
                    attr: {
                        class: 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none',
                        placeholder: 'Votre mot de passe'
                    }
                }) }}
                {{ form_errors(form.plainPassword.first) }}
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmer le mot de passe
                </label>
                {{ form_widget(form.plainPassword.second, {
                    attr: {
                        class: 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none',
                        placeholder: 'Confirmez votre mot de passe'
                    }
                }) }}
                {{ form_errors(form.plainPassword.second) }}
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                Créer mon compte
            </button>

        {{ form_end(form, { render_rest: false }) }}

        <p class="mt-4 text-center text-sm text-gray-600">
            Déjà un compte ?
            <a href="{{ path('app_login') }}"
               class="text-blue-600 hover:underline">
                Se connecter
            </a>
        </p>

    </div>
</div>
{% endblock %}
```

---

# 27. Lancer le serveur Symfony

```bash
php -S 127.0.0.1:8000 -t public
```

Rôle :

* lance l’application localement

Accès :

```txt
http://127.0.0.1:8000
```

---

# 28. Tester l’application

Pages à tester :

```txt
/register
/login
/book
/book/new
```

Rôle :

* `/register` : créer un compte
* `/login` : se connecter
* `/book` : voir les livres
* `/book/new` : ajouter un livre

---

# 29. Protéger les routes Book

Dans `config/packages/security.yaml` :

```yaml
access_control:
    - { path: ^/book, roles: ROLE_USER }
```

Rôle :

* oblige l’utilisateur à être connecté pour gérer les livres

---

# 30. Commandes utiles

```bash
php bin/console cache:clear
php bin/console debug:router
php bin/console doctrine:query:sql "SELECT 1"
php bin/console make:entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console make:crud
php bin/console make:form
php bin/console make:controller
```

---

# 31. Workflow quotidien

À chaque fois que l’on reprend le projet :

```bash
composer install
yarn install
php bin/console doctrine:migrations:migrate
yarn watch
php -S 127.0.0.1:8000 -t public
```

---

# 32. Structure attendue du projet

```txt
symfony-books/
├── assets/
│   ├── app.js
│   └── styles/
│       └── app.css
├── config/
├── migrations/
├── public/
│   └── build/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   └── Security/
├── templates/
│   ├── base.html.twig
│   ├── book/
│   ├── registration/
│   └── security/
├── .env
├── .env.local
├── composer.json
├── package.json
├── tailwind.config.js
└── webpack.config.js
```

---

# 33. Résumé des grandes étapes

```txt
1. Créer projet Symfony
2. Installer webapp
3. Configurer MySQL
4. Créer User
5. Créer login/register
6. Créer Book
7. Créer migration
8. Générer CRUD Book
9. Installer Encore + Tailwind
10. Compiler CSS
11. Lancer serveur
12. Tester l’application
```

---

# 34. Bonnes pratiques

* Séparer logique métier et contrôleurs
* Utiliser des services Symfony pour la logique métier
* Éviter la logique métier dans Twig
* Utiliser Doctrine proprement
* Structurer Controller / Service / Repository
* Utiliser Tailwind pour un style cohérent

---

# 35. Auteur

Projet Symfony 7.4 - apprentissage fullstack (Backend + Frontend)
