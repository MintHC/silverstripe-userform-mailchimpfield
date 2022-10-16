#MailChimp UserForm Field
Adds a custom field to UserForms which allows you to select a
list from MailChimp and subscribe on submission.

### Installation
Via composer
```
composer require swordfox/silverstripe-userform-mailchimpfield
```
##### Configuration
To connect to your MailChimp field you will need to set two fields in your config.yml.
```
EditableMailChimpField:
  api_key: 'API KEY GOES HERE'
```
^ These setting can be found in client settings area in MailChimp.

##### Customisation
You can also change what type of field is actually used on the UserForm.
By Default it's a checkbox field. You can change this via your config.yml OR via the CMS per form.

##### Extensions
There are a few extension hooks which can be useful to handle data before and after saving throughout the process.
+ `$this->extend('beforeValueFromData', $data)`
+ `$this->extend('afterValueFromData', $data)`
+ `$this->extend('updateLists', $data)`

![field configuration example](https://i.ibb.co/mXXykfj/Editable-Mail-Chimp-Field.png)
