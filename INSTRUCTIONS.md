### rename tables
```
eco_com_submitted_documents -> eco_com_submitted_documents_1
economic_complements -> economic_complements_1
```
### drop constraint
```
alter table economic_complements_1 drop constraint  economic_complements_code_unique;
```
### execute seeder
```
php artisan db:seed --class=EconomicComplementSeeder
php artisan db:seed --class=EconomicComplementRolSeeder
```
