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


## Controllers:

### Settleit/Settleit_Controller:
- Check_If_Session_Exists_Function
  - Return Settleit Session and step
    - Create_Settleit_Function
      - Create new UUID
      - Return UUID
- Settleit_Step_Store_Function
  - Take Key and data and saves it
- Settleit_Finalize_Function
  - Finalizes and send
- Settleit_Send_Function
  - takes UUID Sends the Settleit
- Get_Settleit_Function
  - Takes the UUID and shows details
- Settleit_Counter_Offer
  - Get offer

## Database Design:

### settleit
- uuid
- status
- case_number
- dispute_details
- creator_id
- creator_role
- plaintiff - parties_uuid
- defendant - parties_uuid
- settlement_amount
- step

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
- currency
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
- id_verified_id
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
