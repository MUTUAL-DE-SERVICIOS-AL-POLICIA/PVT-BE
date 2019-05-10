### rename tables
```
alter table eco_com_submitted_documents rename to 
eco_com_submitted_documents_1; 
```
### drop constraint
```
alter table economic_complements_1 drop constraint  economic_complements_code_unique;
alter table economic_complements drop constraint economic_complements_affiliate_id_eco_com_procedure_id_unique;
```
### execute seeder
```
php artisan db:seed --class=EconomicComplementSeeder
php artisan db:seed --class=EconomicComplementRolSeeder
```
