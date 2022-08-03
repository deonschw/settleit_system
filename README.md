# SettleIt System

## Todo:

- Complete model files
- Add frontend:
  - Login
  - Register
  - Forgotten Password
  - Dashboard
  - SettleIt
  - Settle Parties
  - Raw Data
- API:
  - Create SettleIt
  - Counter Complete SettleIt
  - Register
  - Login
  - Dashboard View with your Data
- Send Emails:
  - When SettleIt is created
  - When Agreed
  - Share



## Database Design:

### settleit
- uuid
- status
- case_number
- dispute_details
- plaintfill - parties_uuid
- defendant - parties_uuid
- settlement_amount

### settleit_Parties:
- uuid
- settleit_id - fk
- role - plaintfill or defendant
- full_name
- address
- mobile_number
- email_address
- id_verified - bool
- validated_period
- is_legal_representative

### settleit_parties_offer_data
- uuid
- settleit_parties_id
- amount

### settleit_action_log
- uuid
- settleit_id
- settleit_parties_id
- key
- data

### File_data
- uuid
- settleit_id
- file_url

### ID_Verified:
- uuid
- parties_id - fk
- confirmation
- data - json

## Models:

php artisan make:model Settleit/Settleit_Model
php artisan make:model Settleit/Settleit_Parties_Model
php artisan make:model Settleit/Settleit_Parties_Offer_Data_Model
php artisan make:model Settleit/Settleit_Action_Log_Model
php artisan make:model File/File_Data_Model
php artisan make:model ID_Verified/ID_Verified_Model
php artisan make:model Legal/Legal_Data_Model
