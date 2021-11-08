# Le retour du petit CRUD entre amis !
Voilà un petit exemple de CRUD avec une architecture MVC, 
orienté objet et le tout, full vanilla PHP

J'ai mis un peu de bootstrap histoire que ce soit
moins laid

Tout une config Docker est déjà prête avec un Dockerfile 
qui va aller installer les extensions PHP et activer la 
réécriture d'URL de Apache

Il est moins poussé que le micro-framework que j'ai fait plus bas
mais il est quand même fonctionnel

# Problème de DB
Pour une raison que j'ignore, la DB ne se persiste pas correctement avec la table
images

## Run
```
docker-compose up -d
```