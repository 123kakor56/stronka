document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.querySelector('.invoice-canvas');
    if (!canvas) {
        return;
    }

    const xmlPath = 'faktura_przyklad.xml';
    const currencyFormatterCache = new Map();

    const selectEl = (id) => document.getElementById(id);

    const setTextLines = (id, lines) => {
        const el = selectEl(id);
        if (!el) {
            return;
        }
        const filtered = lines.filter((line) => line && line.trim().length > 0);
        if (!filtered.length) {
            el.style.display = 'none';
            return;
        }
        el.style.display = 'block';
        el.textContent = filtered.join('\n');
    };

    const showError = (message) => {
        const el = selectEl('invoice-error');
        if (!el) {
            return;
        }
        el.style.display = 'block';
        el.textContent = message;
    };

    const toNumber = (value) => {
        if (typeof value !== 'string') {
            return Number.NaN;
        }
        const normalized = value.replace(/\s+/g, '').replace(',', '.');
        return Number(normalized);
    };

    const getCurrencyFormatter = (currency) => {
        if (!currency) {
            return new Intl.NumberFormat('pl-PL', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        }
        if (!currencyFormatterCache.has(currency)) {
            currencyFormatterCache.set(
                currency,
                new Intl.NumberFormat('pl-PL', {
                    style: 'currency',
                    currency,
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })
            );
        }
        return currencyFormatterCache.get(currency);
    };

    const formatCurrency = (value, currency) => {
        const num = toNumber(value);
        if (Number.isNaN(num)) {
            return value || '';
        }
        return getCurrencyFormatter(currency).format(num);
    };

    const formatQuantity = (value) => {
        const num = toNumber(value);
        if (Number.isNaN(num)) {
            return value || '';
        }
        return new Intl.NumberFormat('pl-PL', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(num);
    };

    fetch(xmlPath)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Nie można pobrać pliku XML (${response.status})`);
            }
            return response.text();
        })
        .then((xmlString) => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlString, 'application/xml');
            const parserError = xmlDoc.querySelector('parsererror');
            if (parserError) {
                throw new Error('Błąd parsowania pliku XML');
            }

            const text = (selector) => {
                const node = xmlDoc.querySelector(selector);
                return node ? node.textContent.trim() : '';
            };

            const currencyCode = text('Naglowek > Waluta') || 'PLN';

            setTextLines('header-block', [
                `Nr faktury: ${text('Naglowek > Numer')}`,
                `Waluta: ${currencyCode}`,
                `Miejsce wystawienia: ${text('Naglowek > MiejsceWystawienia')}`,
            ]);

            setTextLines('dates-block', [
                `Data wystawienia: ${text('Naglowek > DataWystawienia')}`,
                `Data dostawy/usługi: ${text('Naglowek > DataDostawyLubZakonczeniaUslugi')}`,
                `Termin płatności: ${text('Naglowek > TerminPlatnosci')}`,
                `Forma płatności: ${text('Naglowek > FormaPlatnosci')}`,
            ]);

            const sellerLines = [
                'Sprzedawca:',
                text('Sprzedawca > Nazwa'),
                `NIP: ${text('Sprzedawca > NIP')}`,
                text('Sprzedawca > Adres > Ulica'),
                `${text('Sprzedawca > Adres > KodPocztowy')} ${text('Sprzedawca > Adres > Miasto')}`.trim(),
                text('Sprzedawca > Adres > Kraj'),
            ];
            setTextLines('seller-block', sellerLines);

            const buyerLines = [
                'Nabywca:',
                text('Nabywca > Nazwa'),
                `NIP: ${text('Nabywca > NIP')}`,
                text('Nabywca > Adres > Ulica'),
                `${text('Nabywca > Adres > KodPocztowy')} ${text('Nabywca > Adres > Miasto')}`.trim(),
                text('Nabywca > Adres > Kraj'),
            ];
            setTextLines('buyer-block', buyerLines);

            const bankLines = [
                'Dane do przelewu:',
                text('Sprzedawca > Bank > NazwaBanku'),
                text('Sprzedawca > Bank > NumerKonta'),
            ];
            setTextLines('bank-block', bankLines);

            const itemsContainer = selectEl('invoice-items');
            if (itemsContainer) {
                itemsContainer.innerHTML = '';
                const headerRow = document.createElement('div');
                headerRow.className = 'invoice-item-row header';
                headerRow.innerHTML = [
                    '<span>LP</span>',
                    '<span>Nazwa</span>',
                    '<span>JM</span>',
                    '<span>Ilość</span>',
                    '<span>Cena netto</span>',
                    '<span>Wartość netto</span>',
                    '<span>VAT</span>',
                    '<span>Brutto</span>',
                ].join('');
                itemsContainer.appendChild(headerRow);

                const pozycje = Array.from(xmlDoc.querySelectorAll('Pozycje > Pozycja'));
                if (pozycje.length === 0) {
                    const info = document.createElement('div');
                    info.className = 'invoice-item-row';
                    info.innerHTML = '<span style="grid-column: 1 / -1; text-align: center;">Brak pozycji na fakturze</span>';
                    itemsContainer.appendChild(info);
                } else {
                    pozycje.forEach((pozycja) => {
                        const row = document.createElement('div');
                        row.className = 'invoice-item-row';
                        const values = {
                            lp: textWithContext(pozycja, 'Lp'),
                            nazwa: textWithContext(pozycja, 'Nazwa'),
                            jm: textWithContext(pozycja, 'JM'),
                            ilosc: formatQuantity(textWithContext(pozycja, 'Ilosc')),
                            cenaNetto: formatCurrency(textWithContext(pozycja, 'CenaNetto'), currencyCode),
                            wartoscNetto: formatCurrency(textWithContext(pozycja, 'WartoscNetto'), currencyCode),
                            stawkaVat: textWithContext(pozycja, 'StawkaVAT'),
                            wartoscBrutto: formatCurrency(textWithContext(pozycja, 'WartoscBrutto'), currencyCode),
                        };
                        row.innerHTML = `
                            <span>${escapeHtml(values.lp)}</span>
                            <span>${escapeHtml(values.nazwa)}</span>
                            <span>${escapeHtml(values.jm)}</span>
                            <span class="align-right">${escapeHtml(values.ilosc)}</span>
                            <span class="align-right">${escapeHtml(values.cenaNetto)}</span>
                            <span class="align-right">${escapeHtml(values.wartoscNetto)}</span>
                            <span class="align-right">${escapeHtml(values.stawkaVat)}</span>
                            <span class="align-right">${escapeHtml(values.wartoscBrutto)}</span>
                        `;
                        itemsContainer.appendChild(row);
                    });
                }
            }

            const totalsLines = [
                `Razem netto: ${formatCurrency(text('Podsumowanie > RazemNetto'), currencyCode)}`,
                `Razem VAT: ${formatCurrency(text('Podsumowanie > RazemVAT'), currencyCode)}`,
                `Razem brutto: ${formatCurrency(text('Podsumowanie > RazemBrutto'), currencyCode)}`,
            ];

            const vatBreakdownNodes = Array.from(
                xmlDoc.querySelectorAll('Podsumowanie > RozbicieStawekVAT > Stawka')
            );
            if (vatBreakdownNodes.length) {
                totalsLines.push('', 'Rozbicie VAT:');
                vatBreakdownNodes.forEach((node) => {
                    const code = node.getAttribute('kod') || '';
                    const netto = formatCurrency(textWithContext(node, 'WartoscNetto'), currencyCode);
                    const vat = formatCurrency(textWithContext(node, 'KwotaVAT'), currencyCode);
                    const brutto = formatCurrency(textWithContext(node, 'WartoscBrutto'), currencyCode);
                    totalsLines.push(`${code}: netto ${netto}, VAT ${vat}, brutto ${brutto}`);
                });
            }
            setTextLines('totals-block', totalsLines);

            const notes = text('Uwagi');
            setTextLines('notes-block', notes ? [`Uwagi:`, notes] : []);

            const issuer = text('FaktureWystawil');
            setTextLines('issuer-block', issuer ? [`Fakturę wystawił(a):`, issuer] : []);
        })
        .catch((error) => {
            console.error(error);
            showError(error.message || 'Wystąpił nieoczekiwany błąd.');
        });

    function textWithContext(parent, selector) {
        const node = parent.querySelector(selector);
        return node ? node.textContent.trim() : '';
    }

    function escapeHtml(value) {
        if (value == null) {
            return '';
        }
        return String(value).replace(/[&<>"']/g, (char) => {
            switch (char) {
                case '&':
                    return '&amp;';
                case '<':
                    return '&lt;';
                case '>':
                    return '&gt;';
                case '"':
                    return '&quot;';
                case "'":
                    return '&#39;';
                default:
                    return char;
            }
        });
    }
});
