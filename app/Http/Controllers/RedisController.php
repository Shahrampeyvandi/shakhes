<?php

namespace App\Http\Controllers;

use Goutte;
use Exception;
use Carbon\Carbon;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Holding\Holding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Namad\NamadsDailyReport;

class RedisController extends Controller
{
    public function getmain()
    {
        dd(Cache::get(3));


        // $crawler = Goutte::request('GET', 'https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=-1&CompanyType=-1&Consolidatable=true&IsNotAudited=false&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&TracingNo=-1&search=false');

        // $all = $crawler->html();

        //dd($crawler);

        $arraynamads = [
            "تملي612",
            "ضبدر8058",
            "شستا994",
            "ضصاد8016",
            "تملي710",
            "ضپنا7045",
            "ضخود7054",
            "تسه9803",
            "تملي712",
            "صايپا112",
            "ضسپا8017",
            "ضملي8077",
            "اخزا821",
            "تسه9811",
            "شستا003",
            "ضخود7048",
            "افاد73",
            "صايپا908",
            "طسان7050",
            "اخزا722",
            "اخزا807",
            "ضفلا8084",
            "ضپنا7036",
            "اخزا812",
            "ضصاد8009",
            "تسه9802",
            "ضسپا8033",
            "تسه9805",
            "ضجار7018",
            "ضصاد8018",
            "دعبيدح",
            "شرق1400",
            "اشاد1",
            "شستا992",
            "ضغدر8039",
            "اخزا820",
            "ضصاد8013",
            "ضجار7031",
            "ضفلا8070",
            "ضسپا8020",
            "حكشتيح",
            "ضخود7051",
            "تسه9801",
            "ومللح",
            "ضصاد8020",
            "ضپيك7021",
            "ضفلا8083",
            "تسه9902",
            "ضفلا8060",
            "شجمح",
            "ضخود7065",
            "اخزا716",
            "ضچاد7012",
            "ضفار8040",
            "ضفار8037",
            "ضفلا8076",
            "اخزا809",
            "اشاد9",
            "ضملي8082",
            "ضپيك7033",
            "ضفلا8068",
            "ضملت8025",
            "ضصاد8019",
            "اخزا709",
            "دسانكوح",
            "ضخود7055",
            "ضخود7047",
            "ضفلا8078",
            "شلرد02",
            "ضپاس7019",
            "اخزا815",
            "ضفار8027",
            "ضپنا7047",
            "ضخود7053",
            "صينا205",
            "ضفار8029",
            "شستا004",
            "ضملي8058",
            "اخزا804",
            "ضصاد8021",
            "صخود0004",
            "ضخود7052",
            "ضملي8068",
            "ساروجح",
            "ضصاد8012",
            "گكيشح",
            "ضفلا8086",
            "ضملت8032",
            "ضملي8069",
            "ضغدر8037",
            "ضپنا7042",
            "ضسپا8018",
            "دابورح",
            "اشاد10",
            "ضصاد8011",
            "ضصاد8008",
            "تسه9711",
            "اخزا805",
            "ضغدر8030",
            "ضخود7063",
            "ضخوز7024",
            "مبين012",
            "ضجار7025",
            "حريل02",
            "اخزا624",
            "ضملي8070",
            "ضملت8033",
            "تسه9708",
            "ضخود7058",
            "ضگل8065",
            "تسه9807",
            "ضپاس7017",
            "ضفلا8080",
            "ضجار7016",
            "تسه9903",
            "اخزا623",
            "تسه9812",
            "ضجار7019",
            "ضصاد8017",
            "ضسان7057",
            "ضخود7062",
            "ضسپا8022",
            "اخزا720",
            "ضچاد7020",
            "حكمت01",
            "تملي802",
            "لوتوسح",
            "صبا1401",
            "ضپاس7018",
            "اميدح",
            "اخزا816",
            "اخزا808",
            "اخزا721",
            "تملي709",
            "ضفلا8081",
            "ضپاس7016",
            "اجاد22",
            "ضسپا8016",
            "ضپيك7032",
            "ضفلا8087",
            "ضجار7028",
            "ضغدر8041",
            "ضسپا8007",
            "ضجار7023",
            "ضصاد8014",
            "ضملي8072",
            "ضملي8062",
            "تسه9809",
            "ضخوز7028",
            "ميدكوح",
            "ضپنا7040",
            "صخود412",
            "تسه9810",
            "ضفلا8088",
            "ضملت8035",
            "ضجار7017",
            "ضخود7039",
            "سمگاح",
            "صايپا012",
            "اخزا806",
            "اخزا803",
            "ضصاد8015",
            "ضجار7022",
            "ضفلا8085",
            "اخزا817",
            "تسه9707",
            "اخزا814",
            "ضفلا8082",
            "تسه9712",
            "ضجار7015",
            "كيش1402",
            "كگهرح",
            "ضغدر8040",
            "ضخود7057",
            "ضگل8055",
            "ضپنا7039",
            "ضصاد8022",
            "شستا991",
            "شپتروح",
            "اخزا810",
            "اخزا813",
            "ماهان01",
            "تكشاح",
            "ضجار7026",
            "ضملي8080",
            "ثقزويح",
            "اخزا704",
            "ضملت8037",
            "ضپنا7044",
            "اخزا811",
            "ضخود7056",
            "تسه9901",
            "ضملي8057",
            "ثاختح",
            "ضملت8031",
            "مگچسا104",
            "ضفار8038",
            "ضپنا7037",
            "اخزا718",
            "ضفلا8079",
            "افقح",
            "شستا002",
            "ضصاد8024",
            "تسه9808",
            "ضچاد7024",
            "ضملت8034",
            "اروند04",
            "تسه9806",
            "تسه9709",
            "ضگل8063",
            "ضصاد8010",
            "ضپيك7022",
            "اخزا703",
            "ضگل8059",
            "ضخوز7021",
            "ضپاس7015",
            "تسه9706",
            "تبريز112",
            "اخزا723",
            "ضفار8033",
            "صمسكن912",
            "آ س پح",
            "ضگل8061",
            "ضملت8036",
            "ضسپا8029",
            "ضملي8067",
            "تملي703",
            "ضچاد7023",
            "ضبدر8065",
            "ضخود7041",
            "ضپاس7020",
            "تسه9804",
            "اخزا818",
            "تملي701",
            "ضپاس7014",
            "ختورح", "دالبرح",
            "تابان02",
            "اخزا819",
            "غشوكوح",
            "فخاسح",
            "لوتوس99",
            "سصفها",
            "سلامت6",
            "ثبهساز",
            "زملارد",
            "خلنت",
            "شساخت",
            "غفارس",
            "كويرح",
            "وآفري",
            "سجام",
            "وگردشح",
            "شستا001",
            "خفولا",
            "سشمالح",
            "بزاگرس",
            "تسه9904",
            "پترولح",
            "شراز",
            "شستا993",
            "ولتجار",
            "ثشرقح",
            "بفجر",
            "داناح",
            "دمعيار",
            "دومينو4",
            "ختور",
            "لازما",
            "ثپرديسح",
            "بتك",
            "وتوشه",
            "لخانه",
            "شلرد",
            "كرازي",
            "ونچر",
            "فنوال",
            "سمتاز",
            "شكف",
            "شسم",
            "سلار",
            "گندم2",
            "كرمان02",
            "حبندر",
            "ساذري",
            "نطرين",
            "ثعتما",
            "كرمان00",
            "شتولي",
            "زنگان",
            "شخارك",
            "داريك",
            "مارون4",
            "ثغربح",
            "ضخوز7023",
            "تسه9710",
            "اروند10",
            "تملي711",
            "تپولا",
            "تاصيكو",
            "وآرينح",
            "تملي704",
            "بزاگرسح",
            "تاصيكوح",
            "صخابر102",
            "سيلامح",
            "تسه9905",
            "اراد344",
            "صگل1411",
            "اخزا713",
            "كرونا2",
            "تملي801",
            "تملي707",
            "تملي705",
            "شصدفح",
            "صخود0012",
            "شگويا4",
            "صايپا203",
            "ضگل8057",
            "ضجار7024",
            "ضشنا7075",
            "ضجار7010",
            "ضمخا7032",
            "تملي706",
            "ضپار8013",
            "شپارسح",
            "ضپار8014",
            "ضشنا7076",
            "ضگل8056",
            "ضجار7036",
            "ضخود7050",
            "ضفلا8093",
            "ضخود7069",
            "ضصاد8000",
            "ضملي8081",
            "ضفار8041",
            "ضترا8017",
            "ضفار8036",
            "ثقزويح4",
            "ضچاد7018",
            "ضغدر8047",
            "ضمخا7037",
            "ضغدر8042",
            "ضجار7020",
            "ضبدر8063",
            "ضسپا8021",
            "ضترا8040",
            "ضبدر8062",
            "ضجار7033",
            "ضشنا7048",
            "طغدر8013",
            "ضبدر8060",
            "ضخوز7022",
            "ضبدر8071",
            "ضگل8066",
            "ضپار8019",
            "اراد43",
            "صخود1412",
            "ضسپا8015",
            "دسبحاح",
            "اراد47",
            "ضچاد7022",
            "ضگل8067",
            "ضفلا8089",
            "ضجار7030",
            "اميد99",
            "ضچاد7025",
            "ضبدر8066",
            "ضشنا7055",
            "ضخود7044",
            "ضترا8027",
            "ضشنا7054",
            "ضملي8079",
            "رايان911",
            "كاسپينح",
            "آتي1",
            "شستا005",
            "شگامرن",
            "تملي803",
            "وتوكاح",
            "تملي807",
            "صايپا998",
            "رنيكح",
            "ضبدر8064",
            "ضجار7021",
            "ضچاد7013",
            "ضغدر8044",
            "ضملي8078",
            "ضچاد7017",
            "ضشنا7049",
            "ضغدر8045",
            "ضغدر8048",
            "ضغدر8043",
            "ضخود7060",
            "ضفلا8090",
            "ضفلا8077",
            "ضشنا7074",
            "تملي702",
            "ضفلا8055",
            "ضخود7040",
            "ضچاد7016",
            "ضگل8054",
            "اراد23",
            "كمند2",
            "ضملي8087",
            "ضغدر8046",
            "ضمخا7036",
            "ضمخا7052",
            "دتمادح",
            "ضخوز7030",
            "ضملي8074",
            "ضمخا7051",
            "ضغدر8033",
            "تملي804",
            "كگازح",
            "ضغدر8038",
            "اراد42",
            "ضصاد8001",
            "اخزا902",
            "ضفلا8092",
            "ارفعح",
            "ضفلا8054",
            "ضپنا7043",
            "ضفلا8091",
            "ضپنا7041",
            "ضصاد8002",
            "ضملي8085",
            "ضفلا8075",
            "اعتلاح",
            "ضمخا7053",
            "ضپنا7046",
            "ضگل8058",
            "اراد22",
            "هصادر912",
            "تسه98082",
            "ضجار7027",
            "طفلا8060",
            "صگل411",
            "ضملت8021",
            "گنجين2",
            "هغدير912",
            "ضفار8030",
            "تملي708",
            "ضفار8031",
            "ضملت8026",
            "ضچاد7014",
            "ضچاد7015",
            "مكرج112",
            "ضجار7014",
            "ضملي8073",
            "مبين015",
            "اراد35",
            "افاد81",
            "ضمخا7050",
            "ضملي8076",
            "هفارس912",
            "ضخوز7027",
            "پارسا306",
            "صيدك1404",
            "ضگل8062",
            "اجاد21",
            "اراد11",
            "مبين014",
            "اخزا903",
            "شراز4",
            "ضپاس7000",
            "رهن0104",
            "ضپاس7022",
            "افاد1",
            "ضغدر8036",
            "ضملت8027",
            "ضسپا8038",
            "انرژي3",
            "ضچاد1012",
            "ضخود7067",
            "ضخود7049",
            "هشفن911",
            "هبركت912",
            "همپنا912",
            "هنفت912",
            "ضخود7066",
            "هملت911",
            "ضخود7064",
            "هشير911",
            "ضخود7068",
            "همعاد911",
            "ضبدر8056",
            "ضفار1123",
            "ضپيك7030",
            "ضملي1148",
            "ضترا8031",
            "ضملي1154",
            "تملي805",
            "كيان2",
            "هجم911",
            "هصند911",
            "هرانف912",
            "ضخود7059",
            "انرژي1",
            "ضبدر8061",
            "هخود911",
            "مدير",
            "ضفار1122",
            "هبهرن912",
            "ضفار1126",
            "هترول911",
            "ضخود1043",
            "ضملي1153",
            "ضپاس7021",
            "تسه9906",
            "شاملاح",
            "وسمركز",
            "ضپاس7013",
            "ضخود1044",
            "تملي806",
            "ضملي1150",
            "ضخود1046",
            "ضشنا7085",
            "ضخود1042",
            "ضفار1127",
            "ضملي1151",
            "ضپيك7028",
            "هكوير910",
            "وبيمهح",
            "ضترا8030",
            "ضبدر8068",
            "ضچاد7019",
            "ضخود1041",
            "ضفار1120",
            "ضشنا7082",
            "ضملي1152",
            "ضشنا7056",
            "ضشنا7053",
            "اروند11",
            "ضشنا7071",
            "ضپنا1022",
            "ضغدر8049",
            "ضچاد1018",
            "اشاد102",
            "ضترا8050",
            "ضخود1040",
            "هاباد912",
            "وسزنجان",
            "ضشنا7073",
            "ضپاس7025",
            "ضترا8023",
            "ضسپا8011",
            "ضبدر8069",
            "ضترا8036",
            "ضفار8043",
            "ضپنا1020",
            "ضغدر8035",
            "ضپنا7038",
            "ضشنا7057",
            "ضگل8060",
            "ضپنا1021",
            "ضگل8064",
            "هصيكو402",
            "هپارس912",
        ];


        $namads = Namad::all();

        $arrayezafi = [];
        foreach ($namads as $namad) {

            if (in_array($namad->symbol, $arraynamads)) {
                echo $namad->symbol . ' پاک می شود' . '<br/>';

                $namad->delete();
            } else {
                echo $namad->symbol . ' باقی خواهد ماند' . '<br/>';
            }
        }

        dd($arrayezafi);


        $namad = Namad::find(861);

        $date = Jalalian::forge('now')->format('%Y/%m/%d');

        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=7&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=251103&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        //$ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        dd($result);



        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=15');



        //        $client = new \GuzzleHttp\Client();
        //     //    echo file_get_content('https://www.codal.ir/ReportList.aspx?search&Symbol=%D8%A2%D8%B3%DB%8C%D8%A7&LetterType=-1&Isic=660315&AuditorRef=-1&PageNumber=1&Audited&NotAudited&IsNotAudited=false&Childs&Mains&Publisher=false&CompanyState=0&Category=7&CompanyType=1&Consolidatable&NotConsolidatable');
        // $response = $client->request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=15');
        //         return $response->getBody();


    }
    public function capitalIncrease($namad,$date)
    {

        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=7&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=251103&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new capital increase for namad = '.$namad->symbol . PHP_EOL;
            $capitalincrease = new CapitalIncrease;
            $capitalincrease->namad_id = $namad->id;
            $capitalincrease->from ='assets';
            $capitalincrease->description = $info['Title'];
            $capitalincrease->publish_date = date('Y-m-d');
            $capitalincrease->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $capitalincrease->save();
            $this->create_noty($capitalincrease,$namad);
        }
    }

    public function clarification($namad,$date)
    {
        
        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=2&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=322001&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new clarification for namad = '.$namad->symbol . PHP_EOL;
            $clarification = new clarification;
            $clarification->namad_id = $namad->id;
            $clarification->subject = $info['Title'];
            $clarification->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $clarification->publish_date = date('Y-m-d');
            $clarification->save();
            $this->create_noty($clarification,$namad);
        }
    }

    public function disclor($namad,$date)
    {
        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=422007&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new disclor for namad = '.$namad->symbol . PHP_EOL;
            $disclosures = new Disclosures;
            $disclosures->namad_id = $namad->id;
            $disclosures->publish_date = date('Y-m-d');
            $disclosures->subject = $info['Title'];
            $disclosures->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $disclosures->group = 'a';
            $disclosures->save();
            $this->create_noty($disclosures,$namad);
        }
    }

    private function create_noty($obj,$namad)
    {
        $obj->notifications->create([
            'namad_id'=>$namad->id
        ]);
    }


    public function shakhes()
    {
// $key = 'فملی';

//  $end_char = substr($key, -1);

//             if($end_char == ) {
//                 dd('sdf');
//                str_replace('ی', 'ي', $end_char);
//             }
// dd($key);
  $namads = [];
        if (Cache::has('namadlist')) {
            $namads = Cache::get('namadlist');
        } else {
            $namads = Namad::all();
            Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
        }


        $date=Jalalian::forge('now')->format('%Y/%m/%d');
        // $date="1399/01/01";


        foreach ($namads as $namad) {
            echo 'start namad searching in codal = '.$namad->symbol . PHP_EOL;
            
                $this->capitalIncrease($namad,$date);
           
            
                $this->clarification($namad,$date);
            
           
                $this->disclor($namad,$date);
            
        }























          $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=7&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=1399/01/01&IsNotAudited=false&Isic=251103&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=%D8%AE%D8%B3%D8%A7%D9%BE%D8%A7&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);
        dd($result);

        $status = false;
        $ch = curl_init("http://members.tsetmc.com/tsev2/data/InstTradeHistory.aspx?i=778253364357513&Top=100&A=0");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $result = curl_exec($ch);
        $day_data = explode(';', $result);
        foreach ($day_data as $key => $value) {
            $data = explode('@', $value);
            if (count($data) == 10) {
                $pl = $data[4];
                $pc = $data[3];
                $year = substr($data[0], 0, 4);
                $month = substr($data[0], 4, 2);
                $day = substr($data[0], 6, 2);
                $timestamp = mktime(0, 0, 0, $month, $day, $year);
                $shamsi = Jalalian::forge($timestamp)->format('%d/%m/%y');
                $array[] = [
                    'pl' => $pl,
                    'pc' => $pc,
                    'date' => $shamsi
                ];
            }
        }
        dd($array);

        // dd($data,$year,$month,$day);

        $date = $data[0];
        $pl = $data[4];
        $pc = $data[3];
        // $namads = Namad::all();
        // foreach ($namads as $key => $namad) {
        //     dump(Cache::get($namad->id));
        // }





        //  $inscode = '54277068923045214';
        // if ($inscode) {

        //     $cache = Cache::get($inscode);

        //     if ($cache) {
        //         $cache = Cache::get($inscode);

        //     } else {
        //         $cache = Cache::get(1);
        //     }

        //     do {
        //         try {
        //             $status = false;
        //             $ch = curl_init("http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=$inscode&c=57");
        //             curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //             curl_setopt($ch, CURLOPT_ENCODING, "");
        //             $result = curl_exec($ch);
        //         } catch (\Throwable $th) {
        //             $status = true;
        //             sleep(.5);
        //         }
        //     } while ($status);

        //     $explode_all = explode(';', $result);
        //     $orders = $explode_all[2];
        //     if ($orders) {
        //         $explode_orders = explode('@', $orders);
        //         $explode_orders[1] = $this->format((int) $explode_orders[1]);
        //         $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $cache['minrange'] ? 'gray' : 'black');
        //         $explode_orders[6] = $this->format((int) $explode_orders[6]);
        //         $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $cache['minrange'] ? 'gray' : 'black');
        //         $explode_orders[11] = $this->format((int) $explode_orders[11]);
        //         $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $cache['minrange'] ? 'gray' : 'black');

        //         $explode_orders[4] = $this->format((int) $explode_orders[4]);
        //         $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $cache['maxrange'] ? 'gray' : 'black');
        //         $explode_orders[9] = $this->format((int) $explode_orders[9]);
        //         $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $cache['maxrange'] ? 'gray' : 'black');
        //         $explode_orders[14] = $this->format((int) $explode_orders[14]);
        //         $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $cache['maxrange'] ? 'gray' : 'black');
        //     }


        //     $array['pl'] = $cache['pl'];
        //     $array['pc'] = $cache['pc'];
        //     $array['pf'] = $cache['pf'];
        //     $array['py'] = $cache['py'];
        //     // $array['pmin'] = Cache::get($id)['pmin'];
        //     // $array['pmax'] = Cache::get($id)['pmin'];
        //     $array['tradecount'] = $cache['tradecount'];
        //     $array['N_tradeVol'] =  $this->format($cache['N_tradeVol']);
        //     $array['N_tradecash'] =  $this->format($cache['N_tradecash']);
        //     $array['EPS'] = $cache['EPS'];
        //     $array['P/E'] = $cache['P/E'];
        //     $array['TedadShaham'] = $cache['TedadShaham'];
        //     $array['final_price_value'] = $cache['final_price_value'];
        //     $array['final_price_percent'] = $cache['final_price_percent'];
        //     $array['last_price_change'] = $cache['last_price_change'];
        //     $array['last_price_status'] = $cache['last_price_status'];
        //     $array['pc_change_percent'] = $cache['pc_change_percent'];
        //     $array['pf_change_percent'] = $cache['pf_change_percent'];
        //     // $array['flow'] = Cache::get($id)['flow'];
        //     // $array['ID'] = Cache::get($id)['ID'];
        //     // $array['BaseVol'] =  Cache::get($id)['BaseVol'];
        //     // $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
        //     // $array['personbuy'] = Cache::get($id)['personbuy'];
        //     // $array['legalbuy'] = Cache::get($id)['legalbuy'];
        //     // $array['personsell'] = Cache::get($id)['personsell'];
        //     // $array['legalsell'] = Cache::get($id)['legalsell'];
        //     // $array['personbuycount'] = Cache::get($id)['personbuycount'];
        //     // $array['legalbuycount'] = Cache::get($id)['legalbuycount'];
        //     // $array['personsellcount'] = Cache::get($id)['personsellcount'];
        //     // $array['legalsellcount'] = Cache::get($id)['legalsellcount'];
        //     // $array['person_buy_power'] = Cache::get($id)['person_buy_power'];
        //     // $array['person_sell_power'] = Cache::get($id)['person_sell_power'];
        //     // $array['percent_legal_buy'] = Cache::get($id)['percent_legal_buy'];
        //     // $array['percent_person_sell'] = Cache::get($id)['percent_person_sell'];
        //     // $array['percent_legal_sell'] = Cache::get($id)['percent_legal_sell'];

        // }

        // dd($array);
        // میزان تغییر و قیمت هر سهم
        // foreach ($holding_namads as $key => $pivot) {
        //     $namad_ = Namad::where('id', $pivot->namad_id)->first();

        //   if($namad_) {
        //         $count = $pivot->amount_value * Cache::get($pivot->namad_id)['pc'];
        //     $array['symbol'] = $namad_->symbol;
        //     $array['name'] = $namad_->name;
        //     $array['amount_percent'] = number_format((float)(($count * 100) / $total), 1, '.', '');
        //     $array['final_price_value'] = Cache::get($pivot->namad_id)['pc'];
        //     $array['last_price_percent'] = isset(Cache::get($pivot->namad_id)['payani_change_percent']) ? Cache::get($pivot->namad_id)['payani_change_percent'] : 0;
        //     $array['status'] = Cache::get($pivot->namad_id)['status'];
        //     $all[] = $array;
        //   }
        // }


        // $value = Cache::get('778253364357513');
        // dd($value);
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");

        // $data = explode(',', explode(';', explode('@', $response->getBody())[2])[222])[2];

        // // return $data;

        // $inscode = [
        //     '32097828799138957' => 'شاخص کل',
        //     '5798407779416661' => 'شاخص قیمت',
        //     '67130298613737946' => 'شاخص کل(هم وزن)',
        //     '8384385859414435' => 'شاخص قیمت(هم وزن)',
        //     '49579049405614711' => 'شاخص آزاد شناور',
        //     '62752761908615603' => 'شاخص بازار اول',
        //     '71704845530629737' => 'شاخص بازار دوم',
        // ];

        $namads = Namad::all();

        $array = [];
        foreach ($namads as $namad) {

            $this->saveDailyReport($namad);
        }
    }

    public function saveDailyReport($namad)
    {



        $inscode = $namad->inscode;
        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
        $all = \strip_tags($crawler->html());
        $array = [];
        $array['symbol'] = $namad->symbol;
        $array['name'] = $namad->name;
        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];

        $dailyReport = new NamadsDailyReport;
        $dailyReport->namad_id = $namad->id;

        $array['namad_status'] = trim(explode(',', $main_data)[1]);



        $dailyReport->lastsells = isset($array['lastsells']) ? serialize($array['lastsells']) : '';
        $data['personbuy'] = $buy_sell ?  explode(',', $buy_sell)[0] : 0;
        $data['legalbuy'] = $buy_sell ? explode(',', $buy_sell)[1] : 0;
        $data['personsell'] = $buy_sell ? explode(',', $buy_sell)[3] : 0;
        $data['legalsell'] = $buy_sell ? explode(',', $buy_sell)[4] : 0;
        $data['personbuycount'] = $buy_sell ? explode(',', $buy_sell)[5] : 0;
        $data['legalbuycount'] = $buy_sell ? explode(',', $buy_sell)[6] : 0;
        $data['personsellcount'] = $buy_sell ? explode(',', $buy_sell)[8] : 0;
        $data['legalsellcount'] = $buy_sell ? explode(',', $buy_sell)[9] : 0;

        $array['N_personbuy'] = $data['personbuy'];
        $array['N_legalbuy'] = $data['legalbuy'];
        $array['N_personsell'] = $data['personsell'];
        $array['N_legalsell'] = $data['legalsell'];


        foreach ($data as $key => $item) {
            if ((int)$item > 1000000 && (int)$item < 1000000000) {
                $array[$key] = number_format((int)$item / 1000000, 1) . "M";
            } elseif ((int)$item > 1000000000) {
                $array[$key] = number_format((int)$item / 1000000000, 2) . "B";
            } else {
                $array[$key] = (int)$item;
            }
        }

        if ($data['personbuy'] &&  $data['personbuycount'] &&  $data['personsell'] && $data['personsellcount']) {
            $array['person_buy_power'] = number_format((float)(($data['personbuy'] / $data['personbuycount']) / (($data['personbuy'] / $data['personbuycount']) + ($data['personsell'] / $data['personsellcount']))), 2, '.', '') * 100;
            $array['person_sell_power'] = number_format((float)(100 - $array['person_buy_power']), 0, '.', '');
        } else {
            $array['person_buy_power'] = 0;
            $array['person_sell_power'] = 0;
        }

        $totalbuy = $buy_sell ? explode(',', $buy_sell)[0] + explode(',', $buy_sell)[1] : 0;
        $totalsell = $buy_sell ? explode(',', $buy_sell)[3] + explode(',', $buy_sell)[4] : 0;

        if ($totalbuy && $buy_sell) {
            $array['percent_person_buy'] = number_format((float)((explode(',', $buy_sell)[0] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_person_buy'] = 0;
        }
        if ($totalbuy && $buy_sell) {
            $array['percent_legal_buy'] = number_format((float)((explode(',', $buy_sell)[1] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_legal_buy'] = 0;
        }

        if ($totalsell && $buy_sell) {
            $array['percent_person_sell'] = number_format((float)((explode(',', $buy_sell)[3] * 100) / $totalsell), 0, '.', '');
        } else {
            $array['percent_person_sell'] = 0;
        }
        if ($totalsell && $buy_sell) {
            $array['percent_legal_sell'] = number_format((float)((explode(',', $buy_sell)[4] * 100) / $totalsell), 0, '.', '');
        } else {

            $array['percent_legal_sell'] = 0;
        }


        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];

        $array['time']  = explode(',', $main_data)[0];
        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmax'] = explode(',', $main_data)[6];
        $array['tradecount'] = explode(',', $main_data)[8];
        $array['N_tradeVol'] =  explode(',', $main_data)[9];
        $tradeVOL = explode(',', $main_data)[9];

        if ($orders) {
            $explode_orders = explode('@', $orders);
            $explode_orders[1] = $this->format((int) $explode_orders[1]);
            $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $array['pmin'] ? 'gray' : 'black');
            $explode_orders[6] = $this->format((int) $explode_orders[6]);
            $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $array['pmin'] ? 'gray' : 'black');
            $explode_orders[11] = $this->format((int) $explode_orders[11]);
            $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $array['pmin'] ? 'gray' : 'black');
            $dailyReport->lastbuys = serialize($array['lastbuys']);
            $explode_orders[4] = $this->format((int) $explode_orders[4]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $array['pmax'] ? 'gray' : 'black');
            $explode_orders[9] = $this->format((int) $explode_orders[9]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $array['pmax'] ? 'gray' : 'black');
            $explode_orders[14] = $this->format((int) $explode_orders[14]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $array['pmax'] ? 'gray' : 'black');
        }


        if ((int)$tradeVOL > 1000000 && (int)$tradeVOL < 1000000000) {
            $array['tradevol'] = number_format((int)$tradeVOL / 1000000, 1) . "M";
        } elseif ((int)$tradeVOL > 1000000000) {
            $array['tradevol'] = number_format((int)explode(',', $main_data)[10] / 1000000000, 2) . "B";
        } else {
            $array['tradevol'] = (int)$tradeVOL;
        }

        $tradeCASH = explode(',', $main_data)[10];
        $array['N_tradecash'] = $tradeCASH;
        if ((int)$tradeCASH > 1000000 && (int)$tradeCASH < 1000000000) {
            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000, 1) . "M";
        } elseif ((int)$tradeCASH > 1000000000) {
            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000000, 2) . "B";
        } else {
            $array['tradecash'] =  (int)$tradeCASH;
        }

        if ($array['pl'] && $array['py']) {

            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
        } else {
            $array['status'] = null;
        }
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];

        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=' . $inscode . '');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/',  \explode(',', $all)[34], $matches);
        $array['maxrange'] =  count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/',  \explode(',', $all)[35], $matches);
        $array['minrange'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] =  count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[28], $matches);
        $array['TedadShaham'] =  count($matches) ? $matches[1] : '';




        if ($array['TedadShaham'] && $array['TedadShaham'] !== '' && $array['pl']) {
            $array['MarketCash'] = $array['TedadShaham'] * $array['pl'];
            $array['N_MarketCash'] = $array['MarketCash'];
            if ((int)$array['MarketCash'] > 1000000 && (int)$array['MarketCash'] < 1000000000) {
                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000, 1) . "M";
            } elseif ((int)$array['MarketCash'] > 1000000000) {
                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000000, 2) . "B";
            } else {
                $array['MarketCash'] =  (int)$array['MarketCash'];
            }
        }


        if ($array['TedadShaham'] && $array['TedadShaham'] !== '') {

            if ((int)$array['TedadShaham'] > 1000000 && (int)$array['TedadShaham'] < 1000000000) {
                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000, 1) . "M";
            } elseif ((int)$array['TedadShaham'] > 1000000000) {

                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000000, 2) . "B";
            } else {
                $array['TedadShaham'] =  (int)$array['TedadShaham'];
            }
        }


        preg_match('/\'?(-?\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';
        $array['P/E'] = isset($array['EPS']) && $array['EPS'] ? number_format(($array['pc'] / $array['EPS']), 2, '.', '') : '';
        preg_match('/=\'?(\d+)/', $explode[38], $matches);
        $array['minweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[39], $matches);
        $array['maxweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[42], $matches);
        $array['N_monthAVG'] = count($matches) ? $matches[1] : '';


        if ($array['N_monthAVG']) {

            if ((int)$array['N_monthAVG'] > 1000000 && (int)$array['N_monthAVG'] < 1000000000) {
                $array['monthAVG'] =  number_format((int)$array['N_monthAVG'] / 1000000, 1) . "M";
            } elseif ((int)$array['N_monthAVG'] > 1000000000) {

                $array['monthAVG'] =  number_format((int)$array['N_monthAVG'] / 1000000000, 2) . "B";
            } else {
                $array['monthAVG'] =  (int)$array['N_monthAVG'];
            }
        }

        preg_match('/\'?(\d+)/', $explode[43], $matches);
        $array['groupPE'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[44], $matches);
        $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

        $array['pc_change_percent'] = isset($array['py']) && $array['py'] !== 0 ?  abs(number_format((float)(($array['pc'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';
        $array['pf_change_percent'] = isset($array['pf']) && $array['py'] !== 0 ?  abs(number_format((float)(($array['pf'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';

        if (isset($array['pl']) && isset($array['py'])) {
            $array['final_price_value'] = $array['pl'];
            $array['final_price_percent'] = $array['py'] ?  abs(number_format((float)(($array['pl'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';
            $array['last_price_change'] = abs($array['pl'] - $array['py']);
            $array['last_price_status'] = ($array['pl'] - $array['py']) > 0 ? '1' : '0';
        } else {
            $array['final_price_value'] = '0';
            $array['final_price_percent'] = '0';
            $array['last_price_change'] = '0';
            $array['last_price_status'] = '0';
        }


        $dailyReport->pmax = isset($array['pmax']) ? $array['pmax'] : '';
        $dailyReport->pmin = isset($array['pmin']) ? $array['pmin'] : '';
        $dailyReport->BaseVol = isset($array['BaseVol']) ? $array['BaseVol'] : '';
        $dailyReport->EPS = isset($array['EPS']) ? $array['EPS'] : '';
        $dailyReport->minweek = isset($array['minweek']) ? $array['minweek'] : '';
        $dailyReport->maxweek = isset($array['maxweek']) ? $array['maxweek'] : '';
        $dailyReport->monthAVG = isset($array['monthAVG']) ? $array['monthAVG'] : '';
        $dailyReport->groupPE = isset($array['groupPE']) ? $array['groupPE'] : '';
        $dailyReport->sahamShenavar = isset($array['sahamShenavar']) ? $array['sahamShenavar'] : '';

        $start = Carbon::parse('09:00')->timestamp;
        $end = Carbon::parse('12:30')->timestamp;
        //$time = Carbon::parse($array['time'])->timestamp;
        $time = Carbon::now()->timestamp;

        //dd([Carbon::now()->timestamp,$start,$end,$time]);






        if (($time > $start) && ($time < $end) &&  ((int)$array['N_tradeVol'] > (int)$array['N_monthAVG'])) {
            $zarib =   (float)((int)$array['N_tradeVol'] / (int)$array['N_monthAVG']);
            if ($zarib > 4 && VolumeTrade::check($namad->id)) {
                // dd($namad->id);

                VolumeTrade::create(['namad_id' => $namad->id, 'trade_vol' => $array['N_tradeVol'], 'month_avg' => $array['N_monthAVG'], 'volume_ratio' => $zarib]);
            } else {
                VolumeTrade::where('namad_id', $namad->id)
                    ->whereDate('created_at', Carbon::today())
                    ->update([
                        'trade_vol' => $array['N_tradeVol'],
                        'month_avg' => $array['N_monthAVG'],
                        'volume_ratio' => $zarib
                    ]);
            }
        }
        // echo $array['pl'] . '<br/>';
        // echo ($array['pl'] - ($array['pl'] * 5) / 100) . ' ';
        // $days = 100;

        // $url = 'http://www.tsetmc.com/tsev2/data/InstTradeHistory.aspx?i=' . $inscode . '&Top=' . $days . '&A=1';
        // $crawler = Goutte::request('GET', $url);
        // $history = \strip_tags($crawler->html());
        // $explode_history = explode(';', $history);
        // foreach ($explode_history as $key => $row) {
        //     (int)$last_price_day = isset(explode('@', $row)[4]) ? explode('@', $row)[4] : '';
        //     $array[] = (int)$last_price_day;
        // }
        // $count =  count($array);
        // $sum = array_sum($array);

        //  $avg = number_format((float)($sum / $count), 2, '.', '');

        // $five_percent = ($array['pl'] * 5) / 100;
        // $min_check = $array['pl'] - $five_percent;
        // $max_check = $array['pl'] + $five_percent;

        // if ($avg > $min_check && $avg < $max_check) {
        //     $oneDayAgo = $array[0];
        //     $twoDayAgo = $array[1];
        //     $threeDayAgo = $array[2];
        //     if($avg > $oneDayAgo && $avg > $twoDayAgo && $avg >  $threeDayAgo ){
        //         MovingAverage::create([
        //             'namad_id' => $namad->id,
        //             'symbol' => $namad->symbol,
        //             'avg' => $avg ,
        //             'status' => 'moghavemat',
        //             'days' => $days
        //         ]);
        //     }
        //      if($avg < $oneDayAgo && $avg < $twoDayAgo && $avg <  $threeDayAgo ){
        //         MovingAverage::create([
        //             'namad_id' => $namad->id,
        //             'symbol' => $namad->symbol,
        //             'avg' => $avg ,
        //             'status' => 'hemayat',
        //             'days' => $days
        //         ]);
        //     }
        // }


        // filter calculate

        if ($buy_sell) {

            $array['filter']['person_most_buy_sell'] = $data['personbuycount'] > 0 && $data['personsellcount'] ? (float)($array['N_personbuy'] / $data['personbuycount']) / (float)($array['N_personsell'] / $data['personsellcount']) : 0;
            $array['filter']['person_most_sell_buy'] = $data['personbuycount'] > 0 && $data['personsellcount'] ? (float)($array['N_personsell'] / $data['personsellcount']) / (float)($array['N_personbuy'] / $data['personbuycount']) : 0;
            $array['filter']['legal_most_buy_sell'] = $data['legalbuycount'] > 0 && $data['legalsellcount'] ? (float)($array['N_legalbuy'] / $data['legalbuycount']) / (float)($array['N_legalsell'] / $data['personsellcount']) : 0;
            $array['filter']['legal_most_sell_buy'] = $data['legalbuycount'] > 0 && $data['legalsellcount'] ? (float)($array['N_legalsell'] / $data['legalsellcount']) / (float)($array['N_legalbuy'] / $data['legalbuycount']) : 0;

            $array['filter']['person_most_sell_buy'] = $data['personsellcount'] > 0 && $data['personbuycount'] > 0 ?  (float)($array['N_personsell'] / $data['personsellcount']) / (float)($array['N_personbuy'] / $data['personbuycount']) : 0;
            $array['filter']['person_buy_avg'] = $data['personbuycount'] > 0  && $data['legalbuycount'] > 0 ?   $array['N_personbuy'] /   (float)($data['personbuycount'] +  $data['legalbuycount']) : 0;
            $array['filter']['person_sell_avg'] = $data['personsellcount'] > 0  && $data['legalsellcount'] > 0 ? $array['N_personsell'] /   (float)($data['personsellcount'] +  $data['legalsellcount']) : 0;
            $array['filter']['legal_most_buy_sell'] = $data['legalsellcount'] > 0 && $data['legalbuycount'] > 0 ?  (float)($array['N_legalbuy'] / $data['legalbuycount']) / (float)($array['N_legalsell'] / $data['legalsellcount']) : 0;
            $array['filter']['legal_most_sell_buy'] = $data['legalsellcount'] > 0 &&  $data['legalbuycount'] > 0 ? (float)($array['N_legalsell'] / $data['legalsellcount']) / (float)($array['N_legalbuy'] / $data['legalbuycount']) : 0;
            $array['filter']['power_person_buy'] = $data['personbuycount'] > 0 ? (int)$array['N_personbuy'] /  $data['personbuycount'] : 0;
            $array['filter']['power_person_sell'] = $data['personsellcount'] > 0 ? (int)$array['N_personsell'] / $data['personsellcount'] : 0;
        }
        dd($array);
    }
}
