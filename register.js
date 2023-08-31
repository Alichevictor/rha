




const csvData = `Country,Currency Code
Afghanistan,AFN
Albania,ALL
Algeria,DZD
Andorra,EUR
Angola,AOA
Antigua and Barbuda,XCD
Argentina,ARS
Armenia,AMD
Australia,AUD
Austria,EUR
Azerbaijan,AZN
Bahamas,BSD
Bahrain,BHD
Bangladesh,BDT
Barbados,BBD
Belarus,BYR
Belgium,EUR
Belize,BZD
Benin,XOF
Bhutan,BTN
Bolivia,BOB
Bosnia and Herzegovina,BAM
Botswana,BWP
Brazil,BRL
Brunei,BND
Bulgaria,BGN
Burkina Faso,XOF
Burundi,BIF
Cabo Verde,CVE
Cambodia,KHR
Cameroon,XAF
Canada,CAD
Central African Republic,XAF
Chad,XAF
Chile,CLP
China,CNY
Colombia,COP
Comoros,KMF
Congo (Congo-Brazzaville),XAF
Costa Rica,CRC
Croatia,HRK
Cuba,CUP
Cyprus,EUR
Czech Republic,CZK
Democratic Republic of the Congo,XAF
Denmark,DKK
Djibouti,DJF
Dominica,XCD
Dominican Republic,DOP
Ecuador,USD
Egypt,EGP
El Salvador,USD
Equatorial Guinea,XAF
Eritrea,ERN
Estonia,EUR
Eswatini,SZL
Ethiopia,ETB
Fiji,FJD
Finland,EUR
France,EUR
Gabon,XAF
Gambia,GMD
Georgia,GEL
Germany,EUR
Ghana,GHS
Greece,EUR
Grenada,XCD
Guatemala,GTQ
Guinea,GNF
Guinea-Bissau,XOF
Guyana,GYD
Haiti,HTG
Honduras,HNL
Hungary,HUF
Iceland,ISK
India,INR
Indonesia,IDR
Iran,IRR
Iraq,IQD
Ireland,EUR
Israel,ILS
Italy,EUR
Jamaica,JMD
Japan,JPY
Jordan,JOD
Kazakhstan,KZT
Kenya,KES
Kiribati,AUD
Korea, North,KPW
Korea, South,KRW
Kosovo,EUR
Kuwait,KWD
Kyrgyzstan,KGS
Laos,LAK
Latvia,EUR
Lebanon,LBP
Lesotho,LSL
Liberia,LRD
Libya,LYD
Liechtenstein,CHF
Lithuania,EUR
Luxembourg,EUR
Madagascar,MGA
Malawi,MWK
Malaysia,MYR
Maldives,MVR
Mali,XOF
Malta,EUR
Marshall Islands,USD
Mauritania,MRU
Mauritius,MUR
Mexico,MXN
Micronesia,USD
Moldova,MDL
Monaco,EUR
Mongolia,MNT
Montenegro,EUR
Morocco,MAD
Mozambique,MZN
Myanmar (Burma),MMK
Namibia,NAD
Nauru,AUD
Nepal,NPR
Netherlands,EUR
New Zealand,NZD
Nicaragua,NIO
Niger,XOF
Nigeria,NGN
North Macedonia,MKD
Norway,NOK
Oman,OMR
Pakistan,PKR
Palau,USD
Panama,PAB
Papua New Guinea,PGK
Paraguay,PYG
Peru,PEN
Philippines,PHP
Poland,PLN
Portugal,EUR
Qatar,QAR
Romania,RON
Russia,RUB
Rwanda,RWF
Saint Kitts and Nevis,XCD
Saint Lucia,XCD
Saint Vincent and the Grenadines,XCD
Samoa,WST
San Marino,EUR
Sao Tome and Principe,STN
Saudi Arabia,SAR
Senegal,XOF
Serbia,RSD
Seychelles,SCR
Sierra Leone,SLL
Singapore,SGD
Slovakia,EUR
Slovenia,EUR
Solomon Islands,SBD
Somalia,SOS
South Africa,ZAR
South Sudan,SSP
Spain,EUR
Sri Lanka,LKR
Sudan,SDG
Suriname,SRD
Sweden,SEK
Switzerland,CHF
Syria,SYP
Taiwan,TWD
Tajikistan,TJS
Tanzania,TZS
Thailand,THB
Timor-Leste,USD
Togo,XOF
Tonga,TOP
Trinidad and Tobago,TTD
Tunisia,TND
Turkey,TRY
Turkmenistan,TMT
Tuvalu,AUD
Uganda,UGX
Ukraine,UAH
United Arab Emirates,AED
United Kingdom,GBP
United States,USD
Uruguay,UYU
Uzbekistan,UZS
Vanuatu,VUV
Vatican City,EUR
Venezuela,VEF
Vietnam,VND
Yemen,YER
Zambia,ZMW
Zimbabwe,ZWL
`;

const currencySelect = document.getElementById('currencySelect');
const dataLines = csvData.trim().split('\n');
const headers = dataLines.shift().split(',');

dataLines.forEach(line => {
  const [country, currencyCode] = line.split(',');
  const option = document.createElement('option');
  option.value = currencyCode;
  option.textContent = `${country} (${currencyCode})`;
  currencySelect.appendChild(option);
});

function validateForm() {
  const form = document.getElementById("reg"); // Change this ID to match your form's ID

  // Get all input and select elements within the form
  const formElements = form.querySelectorAll("input, select");

  // Flag to track if any field is empty
  let isValid = true;

  // Loop through each element and check if it's empty
  for (const element of formElements) {
      if (element.value.trim() === "") {
          isValid = false;
          // Add a red border or some visual indicator to highlight empty fields
          element.style.border = "1.5px solid red";
      } else {
          // Reset the style if the field is not empty
          element.style.border = "";
      }
  }

  if (!isValid) {
      
  }

  return isValid;
}
