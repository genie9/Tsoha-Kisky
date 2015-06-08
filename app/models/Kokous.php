<?php

class Kokous extends BaseModel {

    public $pvm, $aika, $paikka, $tyyppi, $muuta, $hal_id, $jasen_id;

    public function construct($attributes) {
        parent::construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Kokous');
        $query->execute();
        $rows = $query->fetchAll();
        $kokoukset = array();

        foreach ($rows as $row) {
            $kokoukset[] = new Kokous(array(
                'pvm' => $row['pvm'],
                'aika' => $row['aika'],
                'paikka' => $row['paikka'],
                'tyyppi' => $row['tyyppi'],
                'muuta' => $row['muuta'],
                'hal_id' => $row['hal_id']));
        }
        return $kokoukset;
    }

    public static function find_by_pvm($pvm) {
        $querry = DB::connection()->prepare('SELECT * FROM Kokous WHERE pvm=$pvm LIMIT 1');
        $querry->execute(array('pvm' => $pvm));
        $row = $querry->fetch();

        if ($row) {
            $kokous = new Kokous(array(
                'pvm' => $row['pvm'],
                'aika' => $row['aika'],
                'paikka' => $row['paikka'],
                'tyyppi' => $row['tyyppi'],
                'muuta' => $row['muuta'],
                'hal_id' => $row['hal_id']));
            return $kokous;
        }
        return NULL;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kokous (pvm, aika, paikka, tyyppi) 
            VALUES (:pvm, :aika, :paikka, :tyyppi) RETURNING id');
        $query->execute(array(
            'pvm' => $this->pvm, 
            'aika' => $this->aika, 
            'paikka' => $this->paikka,
            'tyyppi' => $this->tyyppi));
        
        $row = $query->fetch();
        $this->id = $row['id'];
                
        $osallistuja = new Osallistuja(array(

        ));
        $osallistuja->save($this->id, $this->jasen_id);
    }

}