<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Goutte;

class DailyReportScheduler
{


    public function __invoke()
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");

        $datas = explode(';', explode('@', $response->getBody())[2]);

        foreach ($datas as $data) {
            try {
                $datafor = explode(',', $data);
                $this->saveDailyReport($datafor);
            } catch (Exception $e) {
            }
        }
    }

    public function saveDailyReport($data)
    {

        $inscode = $data[0];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
        $all = \strip_tags($crawler->html());

        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];



        $explode_orders = explode('@', $orders);
        $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);

        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);


        $array['personbuy'] = explode(',', $buy_sell)[0];
        $array['legalbuy'] = explode(',', $buy_sell)[1];
        $array['personsell'] = explode(',', $buy_sell)[3];
        $array['legalsell'] = explode(',', $buy_sell)[4];
        $array['personbuycount'] = explode(',', $buy_sell)[5];
        $array['legalbuycount'] = explode(',', $buy_sell)[6];
        $array['personsellcount'] = explode(',', $buy_sell)[8];
        $array['legalsellcount'] = explode(',', $buy_sell)[9];





        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmax'] = explode(',', $main_data)[6];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmin'] = explode(',', $main_data)[8];
        $array['tradevol'] = explode(',', $main_data)[9];
        $array['tradecash'] = explode(',', $main_data)[10];



        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=' . $inscode . '');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[38], $matches);
        $array['minweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[39], $matches);
        $array['maxweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[42], $matches);
        $array['monthAVG'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[43], $matches);
        $array['groupPE'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[44], $matches);
        $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

        $dailyReport = new NamadsDailyReport;
        $dailyReport->lastbuys = serialize($array['lastbuys']);
        $dailyReport->lastsells = serialize($array['lastsells']);
        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];
        $dailyReport->pmax = $array['pmax'];
        $dailyReport->pmin = $array['pmin'];
        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];
        $dailyReport->BaseVol = $array['BaseVol'];
        $dailyReport->EPS = $array['EPS'];
        $dailyReport->minweek = $array['minweek'];
        $dailyReport->maxweek = $array['maxweek'];
        $dailyReport->monthAVG = $array['monthAVG'];
        $dailyReport->groupPE = $array['groupPE'];
        $dailyReport->sahamShenavar = $array['sahamShenavar'];


        $namad = Namad::where('inscode', $inscode)->first();
        if (!$namad) {
            $namad = new Namad;
            $namad->symbol = $data[2];
            $namad->name = $data[3];
            $namad->inscode =  $inscode;

            if ($array['flow'] == 1) {
                $namad->flow = 'بورس';
            } else {
                $namad->flow = 'فرابورس';
            }
            if ($this->checksymbol($namad->symbol) && $this->checkname($namad->symbol)) {
                $namad->save();
            } else {
            }
        }

        echo 'namad = ' . $namad->symbol . PHP_EOL;
        $dailyReport->namad_id = $namad->id;
        $dailyReport->save();
    }

    public function checkname($symbol)
    {
        if(!preg_match('/^\d{2}\d*$/',$symbol)){
            return true;
        }else{
            return false;
        }
    }

    public function checksymbol($symbol)
    {
        $arraynamads=[
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
            "ختورح","دالبرح",
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

        if (in_array($symbol, $arraynamads)) {

            return false;
        } else {
            return true;
        }
    }
}
