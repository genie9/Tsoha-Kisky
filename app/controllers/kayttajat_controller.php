<?php

class KayttajatController extends BaseController {

    public static function index() {
        View::make('base.html');
    }

    public static function jasenet() {
        $jasenet = Jasen::all();
        View::make('jasenet.html', array('jasenet' => $jasenet));
    }

    public static function rek_lomake() {
        View::make('rekisterointi.html');
    }

    public static function rek_uusi() {
        $params = $_POST;

        $attributes = (array(
            'nimi' => $params['nimi'],
            'email' => $params['email'],
            'sala' => $params['sala'],
            'katuosoite' => $params['katuosoite'],
            'posti' => $params['posti'],
            'puhelin' => $params['puhelin'],
            'syntyma' => $params['syntyma'],
            'huoltaja' => $params['huoltaja'],
            'laji' => $params['laji'],
            'seura' => $params['seura']
        ));
        if(Jasen::find_by_email($_POST['email']) != NULL){
            Redirect::to('/login', array('message' => 'Olet jo rekisteröitynyt, voit jatkaa kirjautumalla sisään'));
        }
        
        $jasen = new Jasen($attributes);
        
        $errors = $jasen->errors();

        if (count($errors) == 0) {
            $jasen->save();
            
            $kayttaja = $jasen->authenticate($jasen->nimi, $jasen->sala);
            $_SESSION['kayttaja'] = $kayttaja->jasen_id;

            Redirect::to('/profiili/' . $jasen->jasen_id, array('message' => 'Hakemuksesi käsitellään seuraavassa hallituksen kokouksessa'));
        } else {
            View::make('rekisterointi.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function login() {
        View::make('login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $kayttaja = Jasen::authenticate($params['nimi'], $params['sala']);

        if (!$kayttaja) {
            View::make('login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'nimi' => $params['nimi']));
        } else {
            $_SESSION['kayttaja'] = $kayttaja->jasen_id;

            Redirect::to('/profiili/' . $kayttaja->jasen_id, array('message' => 'Tervetuloa takaisin ' . $kayttaja->nimi . '!'));
        }
    }

    public static function logout() {
        $_SESSION['kayttaja'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
