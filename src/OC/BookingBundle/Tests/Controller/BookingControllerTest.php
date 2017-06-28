<?php
namespace OC\BookingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Stripe\Stripe;
use Stripe\Token;

class BookingControllerTest extends WebTestCase
{
    //private $url = 'https://louvre.lignedemire.eu';
    private $url = '';

    /**
     * Test Complet d'une commande billet
     *
     * @return [type] [description]
     */
    public function testBooking()
    {

        // Etape 1 - Chargement de la page
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', $this->url);
        $client->followRedirects(true);
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("oc_bookingbundle_ticket")')->count()
        );

        // Etape 1 - Selection des billets
        $form = $crawler->selectButton('oc_bookingbundle_ticket_save')->form();
        $values = $form->getPhpValues();
        $values['oc_bookingbundle_ticket']['duration'] = 2;
        $values['oc_bookingbundle_ticket']['nbticket'] = 1;
        //$values['oc_bookingbundle_ticket']['visit'] = '';
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        //$crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("visitorsTable")')->count()
        );
        

        // Etape 2 - Saisie des visiteurs
        $form = $crawler->selectButton('oc_bookingbundle_visitors_save')->form();
        $values = $form->getPhpValues();

        $values['oc_bookingbundle_visitors']['visitors'][0]['name'] = 'name';
        $values['oc_bookingbundle_visitors']['visitors'][0]['surname'] = 'surname';
        $values['oc_bookingbundle_visitors']['visitors'][0]['birthday'] = '26/11/1963';
        $values['oc_bookingbundle_visitors']['visitors'][0]['country'] = 'AF';

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("payment-form")')->count()
        );

        Stripe::setApiKey("sk_test_CZT8FaxCvhqRgJmIJYGdUFzs");

        // Etape 3 - Paiement
        $testDate = new \DateTime('now +1 year');
        $form = $crawler->selectButton('payment_save')->form();
        $values = $form->getPhpValues();
        $values['payment']['email'] = 'ps.augereau@gmail.com';
        $values['payment']['name'] = 'AUGEREAU';
        $values['payment']['cardno'] = '4242424242424242';
        $values['payment']['expmonth'] = '01';
        $values['payment']['expyear'] = $testDate->format('Y');
        $values['payment']['cvv'] = '123';

        Stripe::setApiKey("sk_test_CZT8FaxCvhqRgJmIJYGdUFzs");
        $token = Token::create(array(
            "card" => array(
                "number" => "4242424242424242",
                "exp_month" => '01',
                "exp_year" => $testDate->format('Y'),
                "cvc" => "123"
            )
        ));

        $values['payment']['token'] =  $token['id'];
        $profile = $client->getProfile();
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test de l'envoi du mail (qui n'est pas envoyé voir config_test.yml)
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('ps.augereau@gmail.com', key($message->getTo()));
    }


    /**
     * Test de réservation pour les jours de fermeture
     *
     * @return [type] [description]
     */
    public function testBookingClosedDay()
    {

        // Etape 1 - Chargement de la page
        $client = static::createClient();
        $crawler = $client->request('GET', $this->url);
        $client->followRedirects(true);
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("oc_bookingbundle_ticket")')->count()
        );


        $form = $crawler->selectButton('oc_bookingbundle_ticket_save')->form();
        $values = $form->getPhpValues();

        // Le passé
        $values['oc_bookingbundle_ticket']['visit'] = '2015-06-24';
        $values['oc_bookingbundle_ticket']['duration'] = 1;
        $values['oc_bookingbundle_ticket']['nbticket'] = 1;
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $this->assertContains('2015-06-24', $client->getResponse()->getContent());

        // Le futur (6 mois)
        $testDate = new \DateTime('+7 month');
        $values['oc_bookingbundle_ticket']['visit'] = $testDate->format('Y-m-d');
        $values['oc_bookingbundle_ticket']['duration'] = 1;
        $values['oc_bookingbundle_ticket']['nbticket'] = 1;
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $this->assertContains($testDate->format('Y-m-d'), $client->getResponse()->getContent());
    }

    /**
     * Test l'accès direct à la deuxième page de la
     * réservation.
     *
     * @return [type] [description]
     */
    public function testBookingRedirect()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->url.'/visitor');
        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->request('GET', $this->url.'/payment');
        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->request('GET', $this->url.'/checkout');
        $this->assertTrue($client->getResponse()->isRedirect());

    }
}

