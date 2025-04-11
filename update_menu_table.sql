-- Script per aggiungere i nuovi campi alla tabella menu esistente
ALTER TABLE menu
ADD COLUMN ingredienti_it VARCHAR(255) AFTER prezzo,
ADD COLUMN ingredienti_en VARCHAR(255) AFTER ingredienti_it,
ADD COLUMN visibile BOOLEAN DEFAULT TRUE AFTER ingredienti_en,
ADD COLUMN extra VARCHAR(255) AFTER visibile;

-- Aggiornamento dei dati esistenti con esempi
-- Milkshake
UPDATE menu 
SET ingredienti_it = 'Caffe espresso, gelato caramello salato, latte', 
    ingredienti_en = 'Espresso coffee, salted caramel ice cream, milk'
WHERE nome = 'Mokaccino' AND tipo = 'milkshake';

UPDATE menu 
SET ingredienti_it = 'Caffe, gelato vanilla del madagascar, latte', 
    ingredienti_en = 'Coffee, madagascar vanilla ice cream, milk',
    extra = 'latte vegetale: +0.50€;meringa: +0.10€'
WHERE nome = 'Caffe salentino' AND tipo = 'milkshake';

UPDATE menu 
SET ingredienti_it = 'Caffè, gelato al cioccolato fondente, latte', 
    ingredienti_en = 'Coffee, dark chocolate ice cream, milk'
WHERE nome = 'Delizia del Madagascar' AND tipo = 'milkshake';

-- Crepes
UPDATE menu 
SET extra = 'panna: +1€'
WHERE nome = 'Nutella' AND tipo = 'crepes';

-- Coppe Gelato
UPDATE menu 
SET ingredienti_it = 'Gelato al pistacchio, panna, cialda croccante', 
    ingredienti_en = 'Pistachio ice cream, whipped cream, crispy wafer'
WHERE nome = 'Pistacchio paradiso' AND tipo = 'coppa gelato';

UPDATE menu 
SET ingredienti_it = 'Gelato alla nocciola, caffè espresso, panna', 
    ingredienti_en = 'Hazelnut ice cream, espresso coffee, whipped cream'
WHERE nome = 'Nocciolamisù' AND tipo = 'coppa gelato';