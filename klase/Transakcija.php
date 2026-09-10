<?php
class Transakcija {

private $OtvorenaKonekcija;

public function __construct($NovaOtvorenaKonekcija)
{
    $this->OtvorenaKonekcija = $NovaOtvorenaKonekcija;
}

public function ZapocniTransakciju()
{
    mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "SET AUTOCOMMIT=0");
    mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "START TRANSACTION");
}

public function ProveriGresku()
{
    return mysqli_error($this->OtvorenaKonekcija->konekcijaDB);
}

public function PonistiTransakciju()
{
    mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "ROLLBACK");
    mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "SET AUTOCOMMIT=1");
}

public function ZavrsiTransakciju($UtvrdjenaGreska)

{
    if (empty($UtvrdjenaGreska)) {
        mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "COMMIT");
    } else {
        mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "ROLLBACK");
    }
    mysqli_query($this->OtvorenaKonekcija->konekcijaDB, "SET AUTOCOMMIT=1");
}

}
