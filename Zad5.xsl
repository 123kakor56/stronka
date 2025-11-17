<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>
    
    <xsl:template match="/">
        <html lang="pl">
            <head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title>Zad5</title>
                <link rel="stylesheet" href="style.css"/>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        background: url('img/Faktura - wzór_page-0001.jpg') no-repeat center center;
                        background-size: contain;
                        min-height: 100vh;
                    }
                </style>
            </head>
            <body>
                <div style="position: relative; width: 100%; max-width: 90%; margin: 0 auto; padding-top: 141.4%;">
                   
                   <!-- Numer faktury -->
                   <div style="position: absolute; top: 14.5%; right: 65%; font-size: 14px; font-weight: bold; text-align: right;">
                       <xsl:value-of select="faktura/numer_faktury"/>
                   </div>
                   
                   <!-- Daty -->
                   <div style="position: absolute; top: 22%; right: 81%; font-size: 12px; text-align: right; line-height: 1.6;">
                      
                       <div><xsl:value-of select="faktura/termin_platnosci"/></div>
                   </div>

                    <div style="position: absolute; top: 14%; right: 23%; font-size: 12px; text-align: right; line-height: 1.6;">
                       <div> <xsl:value-of select="faktura/miejsce_wystawienia    "/>  <xsl:value-of select="faktura/data_wystawienia"/> </div>
                       
                   </div>
                   
                   <!-- Sprzedawca -->
                   <div style="position: absolute; top: 29%; left: 20%; font-size: 18px; line-height: 1.5; max-width: 32%;">
                      
                       <xsl:value-of select="faktura/sprzedawca/nazwa"/><br/>
                       <xsl:value-of select="faktura/sprzedawca/adres"/><br/>
                       NIP: <xsl:value-of select="faktura/sprzedawca/NIP"/>
                   </div>
                   
                   <!-- Nabywca -->
                   <div style="position: absolute; top: 29%; right: 19%; font-size: 18px; line-height: 1.5; max-width: 32%; text-align: left;">
                      
                       <xsl:value-of select="faktura/nabywca/nazwa"/><br/>
                       <xsl:value-of select="faktura/nabywca/adres"/><br/>
                       NIP: <xsl:value-of select="faktura/nabywca/NIP"/>
                   </div>
                   
                   <!-- Dane bankowe -->
                   <div style="position: absolute; top: 35.5%; left: 20%; font-size: 22px; line-height: 1.5; max-width: 50%;">
                       <strong></strong><br/>
                       <xsl:value-of select="faktura/konto/nazwa_banku"/>
                       <xsl:value-of select="faktura/konto/numer_konta"/>
                   </div>
                   
                   <!-- Tabela usług -->
                   <div style="position: absolute; top: 45%; left: 6%; width: 100%; font-size: 11px;">
                       <table style="width: 100%; border-collapse: collapse;">
                           <thead>
                               <tr style="font-weight: bold; padding-top: 10px;">
                                   <!-- Zmien wartosci padding dla kazdej kolumny osobno: padding: gora prawo dol lewo; -->
                                   <th style="text-align: left; width: 1px; padding: 3px 0px 3px 230px;"></th>
                                   <th style="text-align: center;width: 20px ; padding: 0px 0px 3px 185 px;"></th>
                                   <th style="text-align: left; width: 110px ; padding: 3px 12px 3px 0px;"></th>
                                   <th style="text-align: center;width: 20px ; padding: 3px 12px 3px px;"></th>
                                   <th style="text-align: center; width: 200px ; padding: 3px 5px 3px 20px;"></th>
                               </tr>
                           </thead>
                           <tbody>
                               <xsl:for-each select="faktura/uslugi/usluga">
                                   <tr style="font-weight: bold; height: 45px;  font-size: 13px;">
                                       <!-- Dopasuj padding komorek danych do naglowkow -->
                                       <td style="text-align: left;width: 1px; padding: 3px 0px 3px 230px;"><xsl:value-of select="nazwa"/></td>
                                       <td style="text-align: center; padding:  0px 0px 3px 43px;"><xsl:value-of select="ilosc"/></td>
                                       <td style="text-align: left; padding: 3px 12px 3px 0px;"><xsl:value-of select="cena_jednostkowa"/> PLN</td>
                                       <td style="text-align: center; padding: 3px 12px 3px 0px;"><xsl:value-of select="stawka_VAT"/></td>
                                       <td style="text-align: center; padding: 3px 5px 3px 0px;"><xsl:value-of select="wartosc"/> PLN</td>
                                   </tr>
                               </xsl:for-each>
                           </tbody>
                       </table>
                   </div>
                   
                   <!-- Suma wartosci -->
                   <div style="position: absolute; top: 75%; right: 8%; font-size: 8px; font-weight: bold; text-align: right;">
                        <xsl:value-of select="sum(faktura/uslugi/usluga/wartosc)"/> PLN
                   </div>
                   
                   <!-- Podsumowanie -->
                   <div style="position: absolute; top: 22%; right: 62%; font-size: 12px; text-align: right; line-height: 1.6;">
                       <div> <xsl:value-of select="faktura/forma_platnosci"/></div>
                   </div>
                   
                   <!-- Uwagi -->
                   <div style="position: absolute; top: 73%; left: 18%; font-size: 21px; max-width: 60%;">
                       <xsl:value-of select="faktura/uwagi"/>
                   </div>
                   
                   <!-- Wystawił -->
                   <div style="position: absolute; top: 81%; right: 63%; font-size: 40px; text-align: right;">
                        <xsl:value-of select="faktura/wystawiajacy"/>
                   </div>

                </div>
            </body>
        </html>
    </xsl:template>
    
</xsl:stylesheet>