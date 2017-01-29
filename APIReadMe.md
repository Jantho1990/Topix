# API Documentation
These are the API endpoints Topix uses. As per Laravel convention, all endpoints begin with the /api prefix.

---

## Basic Routes
These are the base routes, unmodified by filters.

### Topics

#### GET /topics
Returns a list of all topics in the database.

#### GET /topics/{id}
Returns a topic from the database by its id.

#### POST /topics/
Creates a topic in the database.

#### PUT /topics/{id}
Edits the topic in the database specified by its id.

#### DELETE /topics/{id}
Deletes the topic in the database specified by its id.

### Tags

#### GET /tags
Returns a list of all tags in the database.

#### GET /tags/{id}
Returns a tag from the database by its id.

#### POST /tags/
Creates a tag in the database.

#### PUT /tags/{id}
Edits the tag in the database specified by its id.

#### DELETE /tags/{id}
Deletes the tag in the database specified by its id.

### Categories

#### GET /categories
Returns a list of all categories in the database.

#### GET /categories/{id}
Returns a category from the database by its id.

#### POST /categories/
Creates a category in the database.

#### PUT /categories/{id}
Edits the category in the database specified by its id.

#### DELETE /categories/{id}
Deletes the category in the database specified by its id.

### Sources
The email accounts which send topics to the mailbox.

#### GET /sources
Returns a list of all sources in the database.

#### GET /sources/{id}
Returns a source from the database by its id.

#### POST /sources/
Creates a source in the database.

#### PUT /sources/{id}
Edits the source in the database specified by its id.

#### DELETE /sources/{id}
Deletes the source in the database specified by its id.

### Mailboxes
The email mailboxes which receive topic emails.

#### GET /mailboxes
Returns a list of all mailboxes in the database.

#### GET /mailboxes/{id}
Returns a mailbox from the database by its id.

#### POST /mailboxes/
Creates a mailbox in the database.

#### PUT /mailboxes/{id}
Edits the mailbox in the database specified by its id.

#### DELETE /mailboxes/{id}
Deletes the mailbox in the database specified by its id.

### Users
The users of the app.

#### GET /users
Returns a list of all users in the database.

#### GET /users/{id}
Returns a user from the database by its id.

#### POST /users/
Creates a user in the database.

#### PUT /users/{id}
Edits the user in the database specified by its id.

#### DELETE /users/{id}
Deletes the user in the database specified by its id.

---

## Filtered Routes
Filters add modifiers to alter what resources get returned.

### Pagination ?p={number}
#### topics, categories, tags, sources, mailboxes, users
Returns paginated results of a resource. Useful if you don't want to tie up the network with a large API request.

### Action ?a={action}
#### topics, categories, tags, sources, mailboxes, users
Performs the specified action on a resources and returns the result. This is useful for adding custom functionality that you don't want to dedicate a new endpoint to, like resource counts.

### Tags ?tags={tag1, tag2, etc}
#### topics, categories
Returns a list of resources that are associated with the specified tags.

### Categories ?categories={category1, category2, etc}
#### topics, tags
Returns a list of resources that are associated with the specified categories.

---

## Actions
This is a list of actions you can use with the `action` filter.

### Count ?a=count
#### topics, categories, tags, sources, mailboxes, users
Returns the total number of a resource.
