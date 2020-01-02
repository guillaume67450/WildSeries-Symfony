
Quête 9 :
https://www.loom.com/share/2ff84f7bfbe04ac68b62aa91524d4785

Critères de validation :

Le code est disponible sur un repository GitHub, avec une branche correspondant à cette quête.
Un lien vidéo est présent sur un fichier README.md à la racine du projet.
Sur la vidéo, l’utilisateur navigue de Séries en saisons
Sur la vidéo, l’utilisateur navigue de saisons en épisodes
Avoir les deux classes Season et Episode
Les annotations inversedBy et mappedBy sont présentes dans toutes les relations
Les méthodes addProgram() et removeProgram() sont présentes dans l'entité Category, comme présenté dans la quête,
Les méthodes addSeason(), removeSeason() et getSeasons()sont présentes dans l'entité Program, comme demandé dans le challenge,
Les méthodes addEpisode(), removeEpisode() et getEpisodes()sont présentes dans l'entité Season, comme demandé dans le challenge,
Les méthodes getProgram() et setProgram()sont présentes dans l'entité Season, comme demandé dans le challenge,
Les méthodes getSeason() et setSetSeason()sont présentes dans l'entité Episode, comme demandé dans le challenge,
La méthode getProgram() est utilisée dans WildController au niveau de la méthode showBySeason(), pour récupérer la série associée à la saison affichée.
La méthode getEpisodes() est utilisée dans WildController au niveau de la méthode showBySeason(), pour récupérer les épisodes.

La méthode getEpisodes() est utilisée dans WildController au niveau de la méthode showBySeason(), pour récupérer les épisodes.

Quête 10 :
https://www.loom.com/share/3c475b4ffdf445969234f729ce1c452d

Critères de validation :

•
Dans WildController, la méthode showEpisode() permet de récupérer un objet episode via le param converter, à partir de l’id * en paramètre de route,

•
Dans showEpisode(), la saison et la série associées à l’épisode sont toujours récupérées par l’appel à $episode->getSeason(); et $season->getProgram,

•
La méthode rend une vue affichant le nom de la série, ses infos, ainsi qu’un lien vers la page de détails de la saison et un lien vers la page détails du programme associé.

•
Le code est disponible sur un repository GitHub, avec une branche correspondant à cette quête.

•
La fonctionnalité est démontrée via une vidéo dont le lien est ajouté au README.md

Quête 11 :

lien de la vidéo :
https://www.loom.com/share/5edc8abd98fd4bfba266cf0115bd5852

Quête 12 :
lien de la vidéo :
https://www.loom.com/share/27a1593337ac42298526f0d5b81d4932

Quête 13 :
lien de la vidéo :
https://www.loom.com/share/e1b1c354dee04809892d02287149c2dd