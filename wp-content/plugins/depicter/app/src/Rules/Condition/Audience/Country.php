<?php

namespace Depicter\Rules\Condition\Audience;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class Country extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Audience_Country';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'Audience';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Visitor Country', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				"label" => __("Afghanistan", 'depicter' ),
				"value" => "AF"
			],
			[
				"label" => __("Åland Islands", 'depicter' ),
				"value" => "AX"
			],
			[
				"label" => __("Albania", 'depicter' ),
				"value" => "AL"
			],
			[
				"label" => __("Algeria", 'depicter' ),
				"value" => "DZ"
			],
			[
				"label" => __("American Samoa", 'depicter' ),
				"value" => "AS"
			],
			[
				"label" => __("Andorra", 'depicter' ),
				"value" => "AD"
			],
			[
				"label" => __("Angola", 'depicter' ),
				"value" => "AO"
			],
			[
				"label" => __("Anguilla", 'depicter' ),
				"value" => "AI"
			],
			[
				"label" => __("Antarctica", 'depicter' ),
				"value" => "AQ"
			],
			[
				"label" => __("Antigua and Barbuda", 'depicter' ),
				"value" => "AG"
			],
			[
				"label" => __("Argentina", 'depicter' ),
				"value" => "AR"
			],
			[
				"label" => __("Armenia", 'depicter' ),
				"value" => "AM"
			],
			[
				"label" => __("Aruba", 'depicter' ),
				"value" => "AW"
			],
			[
				"label" => __("Australia", 'depicter' ),
				"value" => "AU"
			],
			[
				"label" => __("Austria", 'depicter' ),
				"value" => "AT"
			],
			[
				"label" => __("Azerbaijan", 'depicter' ),
				"value" => "AZ"
			],
			[
				"label" => __("Bahamas", 'depicter' ),
				"value" => "BS"
			],
			[
				"label" => __("Bahrain", 'depicter' ),
				"value" => "BH"
			],
			[
				"label" => __("Bangladesh", 'depicter' ),
				"value" => "BD"
			],
			[
				"label" => __("Barbados", 'depicter' ),
				"value" => "BB"
			],
			[
				"label" => __("Belarus", 'depicter' ),
				"value" => "BY"
			],
			[
				"label" => __("Belgium", 'depicter' ),
				"value" => "BE"
			],
			[
				"label" => __("Belize", 'depicter' ),
				"value" => "BZ"
			],
			[
				"label" => __("Benin", 'depicter' ),
				"value" => "BJ"
			],
			[
				"label" => __("Bermuda", 'depicter' ),
				"value" => "BM"
			],
			[
				"label" => __("Bhutan", 'depicter' ),
				"value" => "BT"
			],
			[
				"label" => __("Bolivia, Plurinational State of", 'depicter' ),
				"value" => "BO"
			],
			[
				"label" => __("Bonaire, Sint Eustatius and Saba", 'depicter' ),
				"value" => "BQ"
			],
			[
				"label" => __("Bosnia and Herzegovina", 'depicter' ),
				"value" => "BA"
			],
			[
				"label" => __("Botswana", 'depicter' ),
				"value" => "BW"
			],
			[
				"label" => __("Bouvet Island", 'depicter' ),
				"value" => "BV"
			],
			[
				"label" => __("Brazil", 'depicter' ),
				"value" => "BR"
			],
			[
				"label" => __("British Indian Ocean Territory", 'depicter' ),
				"value" => "IO"
			],
			[
				"label" => __("Brunei Darussalam", 'depicter' ),
				"value" => "BN"
			],
			[
				"label" => __("Bulgaria", 'depicter' ),
				"value" => "BG"
			],
			[
				"label" => __("Burkina Faso", 'depicter' ),
				"value" => "BF"
			],
			[
				"label" => __("Burundi", 'depicter' ),
				"value" => "BI"
			],
			[
				"label" => __("Cambodia", 'depicter' ),
				"value" => "KH"
			],
			[
				"label" => __("Cameroon", 'depicter' ),
				"value" => "CM"
			],
			[
				"label" => __("Canada", 'depicter' ),
				"value" => "CA"
			],
			[
				"label" => __("Cape Verde", 'depicter' ),
				"value" => "CV"
			],
			[
				"label" => __("Cayman Islands", 'depicter' ),
				"value" => "KY"
			],
			[
				"label" => __("Central African Republic", 'depicter' ),
				"value" => "CF"
			],
			[
				"label" => __("Chad", 'depicter' ),
				"value" => "TD"
			],
			[
				"label" => __("Chile", 'depicter' ),
				"value" => "CL"
			],
			[
				"label" => __("China", 'depicter' ),
				"value" => "CN"
			],
			[
				"label" => __("Christmas Island", 'depicter' ),
				"value" => "CX"
			],
			[
				"label" => __("Cocos (Keeling) Islands", 'depicter' ),
				"value" => "CC"
			],
			[
				"label" => __("Colombia", 'depicter' ),
				"value" => "CO"
			],
			[
				"label" => __("Comoros", 'depicter' ),
				"value" => "KM"
			],
			[
				"label" => __("Congo", 'depicter' ),
				"value" => "CG"
			],
			[
				"label" => __("Congo, the Democratic Republic of the", 'depicter' ),
				"value" => "CD"
			],
			[
				"label" => __("Cook Islands", 'depicter' ),
				"value" => "CK"
			],
			[
				"label" => __("Costa Rica", 'depicter' ),
				"value" => "CR"
			],
			[
				"label" => __("Côte d'Ivoire", 'depicter' ),
				"value" => "CI"
			],
			[
				"label" => __("Croatia", 'depicter' ),
				"value" => "HR"
			],
			[
				"label" => __("Cuba", 'depicter' ),
				"value" => "CU"
			],
			[
				"label" => __("Curaçao", 'depicter' ),
				"value" => "CW"
			],
			[
				"label" => __("Cyprus", 'depicter' ),
				"value" => "CY"
			],
			[
				"label" => __("Czech Republic", 'depicter' ),
				"value" => "CZ"
			],
			[
				"label" => __("Denmark", 'depicter' ),
				"value" => "DK"
			],
			[
				"label" => __("Djibouti", 'depicter' ),
				"value" => "DJ"
			],
			[
				"label" => __("Dominica", 'depicter' ),
				"value" => "DM"
			],
			[
				"label" => __("Dominican Republic", 'depicter' ),
				"value" => "DO"
			],
			[
				"label" => __("Ecuador", 'depicter' ),
				"value" => "EC"
			],
			[
				"label" => __("Egypt", 'depicter' ),
				"value" => "EG"
			],
			[
				"label" => __("El Salvador", 'depicter' ),
				"value" => "SV"
			],
			[
				"label" => __("Equatorial Guinea", 'depicter' ),
				"value" => "GQ"
			],
			[
				"label" => __("Eritrea", 'depicter' ),
				"value" => "ER"
			],
			[
				"label" => __("Estonia", 'depicter' ),
				"value" => "EE"
			],
			[
				"label" => __("Ethiopia", 'depicter' ),
				"value" => "ET"
			],
			[
				"label" => __("Falkland Islands (Malvinas)", 'depicter' ),
				"value" => "FK"
			],
			[
				"label" => __("Faroe Islands", 'depicter' ),
				"value" => "FO"
			],
			[
				"label" => __("Fiji", 'depicter' ),
				"value" => "FJ"
			],
			[
				"label" => __("Finland", 'depicter' ),
				"value" => "FI"
			],
			[
				"label" => __("France", 'depicter' ),
				"value" => "FR"
			],
			[
				"label" => __("French Guiana", 'depicter' ),
				"value" => "GF"
			],
			[
				"label" => __("French Polynesia", 'depicter' ),
				"value" => "PF"
			],
			[
				"label" => __("French Southern Territories", 'depicter' ),
				"value" => "TF"
			],
			[
				"label" => __("Gabon", 'depicter' ),
				"value" => "GA"
			],
			[
				"label" => __("Gambia", 'depicter' ),
				"value" => "GM"
			],
			[
				"label" => __("Georgia", 'depicter' ),
				"value" => "GE"
			],
			[
				"label" => __("Germany", 'depicter' ),
				"value" => "DE"
			],
			[
				"label" => __("Ghana", 'depicter' ),
				"value" => "GH"
			],
			[
				"label" => __("Gibraltar", 'depicter' ),
				"value" => "GI"
			],
			[
				"label" => __("Greece", 'depicter' ),
				"value" => "GR"
			],
			[
				"label" => __("Greenland", 'depicter' ),
				"value" => "GL"
			],
			[
				"label" => __("Grenada", 'depicter' ),
				"value" => "GD"
			],
			[
				"label" => __("Guadeloupe", 'depicter' ),
				"value" => "GP"
			],
			[
				"label" => __("Guam", 'depicter' ),
				"value" => "GU"
			],
			[
				"label" => __("Guatemala", 'depicter' ),
				"value" => "GT"
			],
			[
				"label" => __("Guernsey", 'depicter' ),
				"value" => "GG"
			],
			[
				"label" => __("Guinea", 'depicter' ),
				"value" => "GN"
			],
			[
				"label" => __("Guinea-Bissau", 'depicter' ),
				"value" => "GW"
			],
			[
				"label" => __("Guyana", 'depicter' ),
				"value" => "GY"
			],
			[
				"label" => __("Haiti", 'depicter' ),
				"value" => "HT"
			],
			[
				"label" => __("Heard Island and McDonald Islands", 'depicter' ),
				"value" => "HM"
			],
			[
				"label" => __("Holy See (Vatican City State)", 'depicter' ),
				"value" => "VA"
			],
			[
				"label" => __("Honduras", 'depicter' ),
				"value" => "HN"
			],
			[
				"label" => __("Hong Kong", 'depicter' ),
				"value" => "HK"
			],
			[
				"label" => __("Hungary", 'depicter' ),
				"value" => "HU"
			],
			[
				"label" => __("Iceland", 'depicter' ),
				"value" => "IS"
			],
			[
				"label" => __("India", 'depicter' ),
				"value" => "IN"
			],
			[
				"label" => __("Indonesia", 'depicter' ),
				"value" => "ID"
			],
			[
				"label" => __("Iran, Islamic Republic of", 'depicter' ),
				"value" => "IR"
			],
			[
				"label" => __("Iraq", 'depicter' ),
				"value" => "IQ"
			],
			[
				"label" => __("Ireland", 'depicter' ),
				"value" => "IE"
			],
			[
				"label" => __("Isle of Man", 'depicter' ),
				"value" => "IM"
			],
			[
				"label" => __("Israel", 'depicter' ),
				"value" => "IL"
			],
			[
				"label" => __("Italy", 'depicter' ),
				"value" => "IT"
			],
			[
				"label" => __("Jamaica", 'depicter' ),
				"value" => "JM"
			],
			[
				"label" => __("Japan", 'depicter' ),
				"value" => "JP"
			],
			[
				"label" => __("Jersey", 'depicter' ),
				"value" => "JE"
			],
			[
				"label" => __("Jordan", 'depicter' ),
				"value" => "JO"
			],
			[
				"label" => __("Kazakhstan", 'depicter' ),
				"value" => "KZ"
			],
			[
				"label" => __("Kenya", 'depicter' ),
				"value" => "KE"
			],
			[
				"label" => __("Kiribati", 'depicter' ),
				"value" => "KI"
			],
			[
				"label" => __("Korea, Democratic People's Republic of", 'depicter' ),
				"value" => "KP"
			],
			[
				"label" => __("Korea, Republic of", 'depicter' ),
				"value" => "KR"
			],
			[
				"label" => __("Kuwait", 'depicter' ),
				"value" => "KW"
			],
			[
				"label" => __("Kyrgyzstan", 'depicter' ),
				"value" => "KG"
			],
			[
				"label" => __("Lao People's Democratic Republic", 'depicter' ),
				"value" => "LA"
			],
			[
				"label" => __("Latvia", 'depicter' ),
				"value" => "LV"
			],
			[
				"label" => __("Lebanon", 'depicter' ),
				"value" => "LB"
			],
			[
				"label" => __("Lesotho", 'depicter' ),
				"value" => "LS"
			],
			[
				"label" => __("Liberia", 'depicter' ),
				"value" => "LR"
			],
			[
				"label" => __("Libya", 'depicter' ),
				"value" => "LY"
			],
			[
				"label" => __("Liechtenstein", 'depicter' ),
				"value" => "LI"
			],
			[
				"label" => __("Lithuania", 'depicter' ),
				"value" => "LT"
			],
			[
				"label" => __("Luxembourg", 'depicter' ),
				"value" => "LU"
			],
			[
				"label" => __("Macao", 'depicter' ),
				"value" => "MO"
			],
			[
				"label" => __("Macedonia, the Former Yugoslav Republic of", 'depicter' ),
				"value" => "MK"
			],
			[
				"label" => __("Madagascar", 'depicter' ),
				"value" => "MG"
			],
			[
				"label" => __("Malawi", 'depicter' ),
				"value" => "MW"
			],
			[
				"label" => __("Malaysia", 'depicter' ),
				"value" => "MY"
			],
			[
				"label" => __("Maldives", 'depicter' ),
				"value" => "MV"
			],
			[
				"label" => __("Mali", 'depicter' ),
				"value" => "ML"
			],
			[
				"label" => __("Malta", 'depicter' ),
				"value" => "MT"
			],
			[
				"label" => __("Marshall Islands", 'depicter' ),
				"value" => "MH"
			],
			[
				"label" => __("Martinique", 'depicter' ),
				"value" => "MQ"
			],
			[
				"label" => __("Mauritania", 'depicter' ),
				"value" => "MR"
			],
			[
				"label" => __("Mauritius", 'depicter' ),
				"value" => "MU"
			],
			[
				"label" => __("Mayotte", 'depicter' ),
				"value" => "YT"
			],
			[
				"label" => __("Mexico", 'depicter' ),
				"value" => "MX"
			],
			[
				"label" => __("Micronesia, Federated States of", 'depicter' ),
				"value" => "FM"
			],
			[
				"label" => __("Moldova, Republic of", 'depicter' ),
				"value" => "MD"
			],
			[
				"label" => __("Monaco", 'depicter' ),
				"value" => "MC"
			],
			[
				"label" => __("Mongolia", 'depicter' ),
				"value" => "MN"
			],
			[
				"label" => __("Montenegro", 'depicter' ),
				"value" => "ME"
			],
			[
				"label" => __("Montserrat", 'depicter' ),
				"value" => "MS"
			],
			[
				"label" => __("Morocco", 'depicter' ),
				"value" => "MA"
			],
			[
				"label" => __("Mozambique", 'depicter' ),
				"value" => "MZ"
			],
			[
				"label" => __("Myanmar", 'depicter' ),
				"value" => "MM"
			],
			[
				"label" => __("Namibia", 'depicter' ),
				"value" => "NA"
			],
			[
				"label" => __("Nauru", 'depicter' ),
				"value" => "NR"
			],
			[
				"label" => __("Nepal", 'depicter' ),
				"value" => "NP"
			],
			[
				"label" => __("Netherlands", 'depicter' ),
				"value" => "NL"
			],
			[
				"label" => __("New Caledonia", 'depicter' ),
				"value" => "NC"
			],
			[
				"label" => __("New Zealand", 'depicter' ),
				"value" => "NZ"
			],
			[
				"label" => __("Nicaragua", 'depicter' ),
				"value" => "NI"
			],
			[
				"label" => __("Niger", 'depicter' ),
				"value" => "NE"
			],
			[
				"label" => __("Nigeria", 'depicter' ),
				"value" => "NG"
			],
			[
				"label" => __("Niue", 'depicter' ),
				"value" => "NU"
			],
			[
				"label" => __("Norfolk Island", 'depicter' ),
				"value" => "NF"
			],
			[
				"label" => __("Northern Mariana Islands", 'depicter' ),
				"value" => "MP"
			],
			[
				"label" => __("Norway", 'depicter' ),
				"value" => "NO"
			],
			[
				"label" => __("Oman", 'depicter' ),
				"value" => "OM"
			],
			[
				"label" => __("Pakistan", 'depicter' ),
				"value" => "PK"
			],
			[
				"label" => __("Palau", 'depicter' ),
				"value" => "PW"
			],
			[
				"label" => __("Palestine, State of", 'depicter' ),
				"value" => "PS"
			],
			[
				"label" => __("Panama", 'depicter' ),
				"value" => "PA"
			],
			[
				"label" => __("Papua New Guinea", 'depicter' ),
				"value" => "PG"
			],
			[
				"label" => __("Paraguay", 'depicter' ),
				"value" => "PY"
			],
			[
				"label" => __("Peru", 'depicter' ),
				"value" => "PE"
			],
			[
				"label" => __("Philippines", 'depicter' ),
				"value" => "PH"
			],
			[
				"label" => __("Pitcairn", 'depicter' ),
				"value" => "PN"
			],
			[
				"label" => __("Poland", 'depicter' ),
				"value" => "PL"
			],
			[
				"label" => __("Portugal", 'depicter' ),
				"value" => "PT"
			],
			[
				"label" => __("Puerto Rico", 'depicter' ),
				"value" => "PR"
			],
			[
				"label" => __("Qatar", 'depicter' ),
				"value" => "QA"
			],
			[
				"label" => __("Réunion", 'depicter' ),
				"value" => "RE"
			],
			[
				"label" => __("Romania", 'depicter' ),
				"value" => "RO"
			],
			[
				"label" => __("Russian Federation", 'depicter' ),
				"value" => "RU"
			],
			[
				"label" => __("Rwanda", 'depicter' ),
				"value" => "RW"
			],
			[
				"label" => __("Saint Barthélemy", 'depicter' ),
				"value" => "BL"
			],
			[
				"label" => __("Saint Helena, Ascension and Tristan da Cunha", 'depicter' ),
				"value" => "SH"
			],
			[
				"label" => __("Saint Kitts and Nevis", 'depicter' ),
				"value" => "KN"
			],
			[
				"label" => __("Saint Lucia", 'depicter' ),
				"value" => "LC"
			],
			[
				"label" => __("Saint Martin (French part)", 'depicter' ),
				"value" => "MF"
			],
			[
				"label" => __("Saint Pierre and Miquelon", 'depicter' ),
				"value" => "PM"
			],
			[
				"label" => __("Saint Vincent and the Grenadines", 'depicter' ),
				"value" => "VC"
			],
			[
				"label" => __("Samoa", 'depicter' ),
				"value" => "WS"
			],
			[
				"label" => __("San Marino", 'depicter' ),
				"value" => "SM"
			],
			[
				"label" => __("Sao Tome and Principe", 'depicter' ),
				"value" => "ST"
			],
			[
				"label" => __("Saudi Arabia", 'depicter' ),
				"value" => "SA"
			],
			[
				"label" => __("Senegal", 'depicter' ),
				"value" => "SN"
			],
			[
				"label" => __("Serbia", 'depicter' ),
				"value" => "RS"
			],
			[
				"label" => __("Seychelles", 'depicter' ),
				"value" => "SC"
			],
			[
				"label" => __("Sierra Leone", 'depicter' ),
				"value" => "SL"
			],
			[
				"label" => __("Singapore", 'depicter' ),
				"value" => "SG"
			],
			[
				"label" => __("Sint Maarten (Dutch part)", 'depicter' ),
				"value" => "SX"
			],
			[
				"label" => __("Slovakia", 'depicter' ),
				"value" => "SK"
			],
			[
				"label" => __("Slovenia", 'depicter' ),
				"value" => "SI"
			],
			[
				"label" => __("Solomon Islands", 'depicter' ),
				"value" => "SB"
			],
			[
				"label" => __("Somalia", 'depicter' ),
				"value" => "SO"
			],
			[
				"label" => __("South Africa", 'depicter' ),
				"value" => "ZA"
			],
			[
				"label" => __("South Georgia and the South Sandwich Islands", 'depicter' ),
				"value" => "GS"
			],
			[
				"label" => __("South Sudan", 'depicter' ),
				"value" => "SS"
			],
			[
				"label" => __("Spain", 'depicter' ),
				"value" => "ES"
			],
			[
				"label" => __("Sri Lanka", 'depicter' ),
				"value" => "LK"
			],
			[
				"label" => __("Sudan", 'depicter' ),
				"value" => "SD"
			],
			[
				"label" => __("Suriname", 'depicter' ),
				"value" => "SR"
			],
			[
				"label" => __("Svalbard and Jan Mayen", 'depicter' ),
				"value" => "SJ"
			],
			[
				"label" => __("Swaziland", 'depicter' ),
				"value" => "SZ"
			],
			[
				"label" => __("Sweden", 'depicter' ),
				"value" => "SE"
			],
			[
				"label" => __("Switzerland", 'depicter' ),
				"value" => "CH"
			],
			[
				"label" => __("Syrian Arab Republic", 'depicter' ),
				"value" => "SY"
			],
			[
				"label" => __("Taiwan, Province of China", 'depicter' ),
				"value" => "TW"
			],
			[
				"label" => __("Tajikistan", 'depicter' ),
				"value" => "TJ"
			],
			[
				"label" => __("Tanzania, United Republic of", 'depicter' ),
				"value" => "TZ"
			],
			[
				"label" => __("Thailand", 'depicter' ),
				"value" => "TH"
			],
			[
				"label" => __("Timor-Leste", 'depicter' ),
				"value" => "TL"
			],
			[
				"label" => __("Togo", 'depicter' ),
				"value" => "TG"
			],
			[
				"label" => __("Tokelau", 'depicter' ),
				"value" => "TK"
			],
			[
				"label" => __("Tonga", 'depicter' ),
				"value" => "TO"
			],
			[
				"label" => __("Trinidad and Tobago", 'depicter' ),
				"value" => "TT"
			],
			[
				"label" => __("Tunisia", 'depicter' ),
				"value" => "TN"
			],
			[
				"label" => __("Turkey", 'depicter' ),
				"value" => "TR"
			],
			[
				"label" => __("Turkmenistan", 'depicter' ),
				"value" => "TM"
			],
			[
				"label" => __("Turks and Caicos Islands", 'depicter' ),
				"value" => "TC"
			],
			[
				"label" => __("Tuvalu", 'depicter' ),
				"value" => "TV"
			],
			[
				"label" => __("Uganda", 'depicter' ),
				"value" => "UG"
			],
			[
				"label" => __("Ukraine", 'depicter' ),
				"value" => "UA"
			],
			[
				"label" => __("United Arab Emirates", 'depicter' ),
				"value" => "AE"
			],
			[
				"label" => __("United Kingdom", 'depicter' ),
				"value" => "GB"
			],
			[
				"label" => __("United States", 'depicter' ),
				"value" => "US"
			],
			[
				"label" => __("United States Minor Outlying Islands", 'depicter' ),
				"value" => "UM"
			],
			[
				"label" => __("Uruguay", 'depicter' ),
				"value" => "UY"
			],
			[
				"label" => __("Uzbekistan", 'depicter' ),
				"value" => "UZ"
			],
			[
				"label" => __("Vanuatu", 'depicter' ),
				"value" => "VU"
			],
			[
				"label" => __("Venezuela, Bolivarian Republic of", 'depicter' ),
				"value" => "VE"
			],
			[
				"label" => __("Viet Nam", 'depicter' ),
				"value" => "VN"
			],
			[
				"label" => __("Virgin Islands, British", 'depicter' ),
				"value" => "VG"
			],
			[
				"label" => __("Virgin Islands, U.S.", 'depicter' ),
				"value" => "VI"
			],
			[
				"label" => __("Wallis and Futuna", 'depicter' ),
				"value" => "WF"
			],
			[
				"label" => __("Western Sahara", 'depicter' ),
				"value" => "EH"
			],
			[
				"label" => __("Yemen", 'depicter' ),
				"value" => "YE"
			],
			[
				"label" => __("Zambia", 'depicter' ),
				"value" => "ZM"
			],
			[
				"label" => __("Zimbabwe", 'depicter' ),
				"value" => "ZW"
			]
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;
		$isIncluded = empty( $value );
		if ( ! $isIncluded ) {
			$countryCode = \Depicter::geoLocate()->getCountry();
			$isIncluded = in_array( $countryCode, $value );
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
