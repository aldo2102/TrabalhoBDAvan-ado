------Links Importantes-------

http://www.devmedia.com.br/utilizando-a-dupla-mongodb-e-php/27798

http://www.vivaolinux.com.br/dica/Instalando-servidor-Apache-+-PHP-+-MySQL-+-phpMyadmin-+-noip-no-Ubuntu-6.10-Server

---------------- Comandos -----
-Download

wget -S "http://arquivos.portaldatransparencia.gov.br/downloads.asp?a=2014&m=09&consulta=BolsaFamiliaFolhaPagamento"

wget -S "http://arquivos.portaldatransparencia.gov.br/downloads.asp?a=2014&m=10&consulta=BolsaFamiliaFolhaPagamento"

-Renomeando

sudo mv  "downloads.asp?a=2014&m=09&consulta=BolsaFamiliaFolhaPagamento" 092014.zip
sudo mv  "downloads.asp?a=2014&m=10&consulta=BolsaFamiliaFolhaPagamento" 102014.zip

-Descompactando 

sudo unzip 092014.zip 
sudo unzip 102014.zip


-Convertes UTF-8

iconv -f ISO-8859-1 -t UTF-8 201410_BolsaFamiliaFolhaPagamento.csv >09-2014-UTF8.tsv
iconv -f ISO-8859-1 -t UTF-8 201410_BolsaFamiliaFolhaPagamento.csv >10-2014-UTF8.tsv


-Importar para o Mongo

mongoimport --db bolsafamilia --collection mes092014 --type tsv --headerline --file 09-2014-UTF8.tsv
mongoimport --db bolsafamilia --collection mes102014 --type tsv --headerline --file 10-2014-UTF8.tsv


---------10/2014----------

MAX: db.getCollection("10/2014").find({ },    { "Valor Parcela": 1, _id: 0 }).sort({"Valor Parcela":-1}).limit(1)

MIN: db.getCollection("10/2014").find({ },    { "Valor Parcela": 1, _id: 0 }).sort({"Valor Parcela":1}).limit(1)

AVG: db.getCollection("10/2014").aggregate([{"$group": {"_id": null, "avg(Valor Parcela)": {"$avg": "$Valor Parcela"}}}])

Total por estados: db.mes102014.aggregate([ { $group: { _id: "$UF", total: { $sum: "$Valor Parcela" }}}] )

-----------09/2014----------


MAX: db.getCollection("09/2014").find({ },    { "Valor Parcela": 1, _id: 0 }).sort({"Valor Parcela":-1}).limit(1)

MIN: db.getCollection("09/2014").find({ },    { "Valor Parcela": 1, _id: 0 }).sort({"Valor Parcela":1}).limit(1)

AVG: db.getCollection("09/2014").aggregate([{"$group": {"_id": null, "avg(Valor Parcela)": {"$avg": "$Valor Parcela"}}}])

Total por estados: db.getCollection("09/2014").aggregate([ { $group: { _id: "$UF", total: { $sum: "$Valor Parcela" }}}] )

-------index-------



db.getCollection("09/2014").ensureIndex({"avg(Valor Parcela)":1})

db.getCollection("10/2014").ensureIndex({"avg(Valor Parcela)":1})

db.getCollection("09/2014").ensureIndex({"UF":1})

db.getCollection("10/2014").ensureIndex({"UF":1})

db.getCollection("09/2014").ensureIndex({"UF":1, "Valor Parcela":-1})

db.getCollection("10/2014").ensureIndex({"UF":1, "Valor Parcela":-1})

db.getCollection("09/2014").ensureIndex({"Valor Parcela":-1})

db.getCollection("10/2014").ensureIndex({"Valor Parcela":-1})

db.getCollection("09/2014").ensureIndex({"Nome Favorecido": 1})

db.getCollection("10/2014").ensureIndex({"Nome Favorecido": 1})
