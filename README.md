# password-generator
Personal Ultimate Password Solution - it generates unique password for service

live demo: https://www.danielkouba.cz/pass

## Motivace

- Chcete bezpečné a silné heslo?
- Nechcete si pamatovat spousty hesel?
- Otravuje vas zdlouhavé psaní dlouhých a složitých hesel?
- Chcete mít pro každou službu unikátní heslo?
- Pokud je alespoň jedna odpověď ANO doporučuji tento generátor!

## Jak to funguje?

- Potřebujete **jediné heslo** (secret)
  - Nemusí obsahovat speciální znaky
  - Nemusí být ani obzvášť složité
  - Samozřejmě i zde platí, čím silnější, tím leší!
  - Doporučuji alespoň 6 znaků, číslici a velké písmeno
- **Zadejte službu**, pro kterou chcete heslo vygenerovat
- Zadejte verzi hesla - pro případ, že služba vyžqduje periodickou změnu hesla, nebo v případě že heslo bylo odhaleno
- **Vygenerové heslo si zkopírujte do schránky** pro použité ve službě
- Vygenerovane heslo si nikam **nemusíte ukládat** (doporučeno).
- Při zadání **stejných parametrů** je vygenerováno vždy **STEJNÉ heslo**.
- Služba a verze hesla lze uložit - bez vašeho secretu jsou nepoužitelné.

## Vlastnosti vygenerovaného hesla

- je vždy **unikátní**
- **formát hesla** by měl vyhovovat snad všem službám
- splňuje vlastnosti [silného hesla](https://passwordsgenerator.net/)
  - Vysoká entropie (nelze ho tipnout, není v žádném slovníku)
  - Délka 16 znaků
  - obsahuje min. 2 speciální znaky
  - obsahuje min. 1 velké písmeno
  - obsahuje min. 1 číslici
  - Nezapamatovatelné
  - Neobsahuje ambivalentní znaky jako z\y
  - Jednosměrné šifrování - z hesla **nelze odvodit secret**, a to i v případě odhalení více hesel
  - Když vám unikne heslo ostatní zůstávají stále bezpečná
  - Parametr "služba" se upravuje pro **eliminaci překlepu** (nezáleží na diakritice ani velikosti písmen)
- Komunikace generátoru je **zašifrována HTTPS** - nelze odchytit secret ani heslo během komunikace se serverem.

## Bezpečností opatření
- **POZOR  riziko tu ovšem je** - pokud server nemáte plně pod kontroloz nelze nijak ověřit, co se děje s daty. na serverech je běžně zapnuté logování a zejména pokud nepoužíváte HTTPS je možné že se ukládají data do nějakého logu, ze kterého mohou být později vytažena SECRETS! 
  - Je důležité si tyto **rizika** uvědomovat, protože **hrozí** prakticky na **každé** stránce, kde zadáváte svoje heslo (**přihlášení**)
  - Přesně z tohoto důvodu je **nezbytné mít pro každou službu unikátní heslo
  - Je to opravdu velmi důležité, protože k **zneužití hesel běžně dochází**.  
  - Koneckonců jestli bylo vaše heslo zneužito si můžete [sami ověřit](https://haveibeenpwned.com/).
  - Čas od času dojde k úniku hesel i [velkým a důvěryhodným společnostem](https://tech.ihned.cz/internet/c1-65860990-bezpecnostni-svodka-unik-hesel-z-mall-cz-neni-tragedie-firma-to-zvladla-na-lepsi-dvojku) jako MALL.CZ.
  - Nevěřte nikomu!
- Pokud se **přihlašujete** na stránkách **bez HTTPS** nebo na **veřejných wifi** měli by jste být  [velmi obezřetní](https://www.lupa.cz/clanky/jak-jde-nejen-na-alza-cz-nakoupit-za-cizi-penize/)
- Odkaz na váš generátor si uložte do záložek a už nikdy nepiště jeho URL do prohlížeče

## Diferenciace & customizace algorytmu
před použitím tohoto algorytmu doporučuji udělat pár zněm

- modifikujte PasswordGenerator::SEED
- můžete modifikovat heslo na základně operací ze $seed1 a $seed2 
   - povoleny jsou deterministické operace ( předvídatelné , non-random )
   
```
//příklad diferenciace

 if(0 == ($seed % 2)) { //pokud je seed sudé číslo..
   $password[0] = strtoupper($this->char($letters,0));
 } else {
   $password[0] = $this->char($specialChars,0));
 }
 if( is_numeric($password[0])){ //pokud je znak číslo
    $password = strrev($password) //heslo pozpátku 
 } else {
   $password = str_replace("a","x",$password) //nahrazení znaku 
 }
```
