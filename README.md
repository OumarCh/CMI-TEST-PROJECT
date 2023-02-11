# CMI test project

Il s'agit dun projet de création d'un service web de discussion et de commentaires d’articles.

## Installation du projet

1. composer install
2. npm install
3. docker-compose up -d
4. npm run build
5. php bin/console lexik:jwt:generate-keypair
6. php bin/console doctrine:schema:update --force
7. php bin/console --env=test doctrine:database:create
8. php bin/console --env=test doctrine:schema:create
9. php bin/console hautelook:fixtures:load --purge-with-truncate
10. php bin/console --env=test hautelook:fixtures:load --purge-with-truncate

## Les tests

Pour jouer les tests, il faut lancer la commande : php bin/phpunit


## API Reference

Prérequis :

| `jwt token` | `string` | **Required**. la clé api |

#### Get api jwt token

```http
  POST /api/login_check
```

| `username` | `string` | **Required**. email |
| `password` | `string` | **Required**. la mot de passe |

#### Get all posts

```http
  GET /api/posts
```

#### Get post

```http
  GET /api/posts/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de l'article     |

#### Create post

```http
  POST /api/posts
```

#### Update post

```http
  PUT /api/posts/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de l'article     |

#### Delete post

```http
  DELETE  /api/posts/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de l'article     |


#### Get all comments

```http
  GET /api/comments
```

#### Get comment

```http
  GET /api/comments/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id du commentaire   |

#### Create comment

```http
  POST /api/comments/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de l'article     |

#### Update comment

```http
  PUT /api/comments/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id du commentaire   |

#### Delete comment

```http
  DELETE  /api/comments/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id du commentaire   |


#### Get all answers

```http
  GET /api/answers
```

#### Get answer

```http
  GET /api/answers/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |

#### Create answer

```http
  POST /api/answers/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id du commentaire   |

#### Update answer

```http
  PUT /api/answers/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |

#### Delete answer

```http
  DELETE  /api/answers/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |

#### Get all users

```http
  GET /api/users
```

#### Get user

```http
  GET /api/users/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |

#### Create user

```http
  POST /users
```

#### Update user

```http
  PUT /api/users/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |

#### Delete user

```http
  DELETE  /api/users/${id}
```

| Paramètre | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id de la réponse    |








