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
                <div style="position: relative; width: 100%; max-width: 1200px; margin: 0 auto; padding-top: 141.4%;">
                   
                   <!-- Numer faktury -->
                   <div style="position: absolute; top: 14.5%; right: 65%; font-size: 14px; font-weight: bold; text-align: right;">
                       <xsl:value-of select="faktura/numer_faktury"/>
                   </div>
                   
                   <!-- Daty -->
                   <div style="position: absolute; top: 22%; right: 89%; font-size: 12px; text-align: right; line-height: 1.6;">
                      
                       <div><xsl:value-of select="faktura/termin_platnosci"/></div>
                   </div>

                    <div style="position: absolute; top: 14%; right: 14%; font-size: 12px; text-align: right; line-height: 1.6;">
                       <div> <xsl:value-of select="faktura/miejsce_wystawienia    "/>  <xsl:value-of select="faktura/data_wystawienia"/> </div>
                       
                   </div>
                   
                   
                   
                   <!-- Nabywca -->
                   <div style="position: absolute; top: 29%; right: 6%; font-size: 18px; line-height: 1.5; max-width: 32%; text-align: left;">
                      
                       <xsl:value-of select="faktura/nabywca/nazwa"/><br/>
                       <xsl:value-of select="faktura/nabywca/adres"/><br/>
                       NIP: <xsl:value-of select="faktura/nabywca/NIP"/>
                   </div>
                   
                   <!-- Dane bankowe -->
                   <div style="position: absolute; top: 33%; left: 6%; font-size: 11px; line-height: 1.5; max-width: 32%;">
                       <strong>Bank:</strong><br/>
                       <xsl:value-of select="faktura/konto/nazwa_banku"/><br/>
                       <xsl:value-of select="faktura/konto/numer_konta"/>
                   </div>
                   
                   <!-- Tabela usług -->
                   <div style="position: absolute; top: 43%; left: 6%; width: 88%; font-size: 10px;">
                       <table style="width: 100%; border-collapse: collapse;">
                           <thead>
                               <tr style="font-weight: bold;">
                                   <th style="width: 4%; text-align: center; padding: 3px;">Lp</th>
                                   <th style="width: 30%; text-align: left; padding: 3px;">Nazwa</th>
                                   <th style="width: 8%; text-align: center; padding: 3px;">Ilość</th>
                                   <th style="width: 12%; text-align: right; padding: 3px;">Cena jedn.</th>
                                   <th style="width: 10%; text-align: center; padding: 3px;">VAT</th>
                                   <th style="width: 15%; text-align: right; padding: 3px;">Wartość</th>
                               </tr>
                           </thead>
                           <tbody>
                               <xsl:for-each select="faktura/uslugi/usluga">
                                   <tr>
                                       <td style="text-align: center; padding: 3px;"><xsl:value-of select="position()"/></td>
                                       <td style="text-align: left; padding: 3px;"><xsl:value-of select="nazwa"/></td>
                                       <td style="text-align: center; padding: 3px;"><xsl:value-of select="ilosc"/></td>
                                       <td style="text-align: right; padding: 3px;"><xsl:value-of select="cena_jednostkowa"/> PLN</td>
                                       <td style="text-align: center; padding: 3px;"><xsl:value-of select="stawka_VAT"/></td>
                                       <td style="text-align: right; padding: 3px;"><xsl:value-of select="wartosc"/> PLN</td>
                                   </tr>
                               </xsl:for-each>
                           </tbody>
                       </table>
                   </div>
                   
                   <!-- Podsumowanie -->
                   <div style="position: absolute; top: 79%; right: 6%; font-size: 12px; text-align: right; line-height: 1.6;">
                       <div>Forma płatności: <xsl:value-of select="faktura/forma_platnosci"/></div>
                   </div>
                   
                   <!-- Uwagi -->
                   <div style="position: absolute; top: 86%; left: 6%; font-size: 11px; max-width: 60%;">
                       <xsl:value-of select="faktura/uwagi"/>
                   </div>
                   
                   <!-- Wystawił -->
                   <div style="position: absolute; top: 92%; right: 6%; font-size: 11px; text-align: right;">
                       Wystawił: <xsl:value-of select="faktura/wystawiajacy"/>
                   </div>

                </div>
            </body>
        </html>
    </xsl:template>
    
</xsl:stylesheet>