<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21/05/2018
 * Time: 10:24
 */

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $objectManager)
    {

        // # trick 1
        $trick = new Trick();

        $trick->setName('Mute Grab');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('grabs'));
        $trick->setDescription('The mute grab was one of the first grabs ever done by skiers and remains the most stylish. Watching a skier suspended in the air with his skis tweaked into that perfect crossed-up position is one of the most beautiful things you\'ll ever see above the snow. It\'s a versatile move that can be thrown off every type of jump, in the halfpipe, and in multiple combinations.

The great part is that it\'s not that hard to do.

Prerequisites:

Being able to hit a jump cleanly

Being able to lift your knees to your chestThe Technique:First, find yourself a good jump. Small- to medium-sized tabletops in the terrain park are a good place to start. At first, simply hit the jump and pull your knees to your chest while in the air. Once you\'ve got that down, add an old-fashioned tip cross. When you\'re feeling comfortable with that, reach down with one hand and grab the opposite ski by the outside edge (i.e., right hand grabs left ski), just in front of your binding toepiece. Make sure you wait until you\'re in the air and balanced before you go for the grab.Your first grabs will probably be more like touches than grabs, but as you get more comfortable with this move, you\'ll want to really grab your ski and tweak it behind you. That\'s the way to achieve the really stylish crossed-up position. Grab with your top two fingers -- you need the others to hold your pole. A final reminder: Lift your knees aggressively toward your chest, as opposed to just reaching down with your hand, and keep your eyes up.Progression:');
        $trick->setFrontPicture('front_mute_grab.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('mute_grab'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('M5NTCfdObfs');

        $trick->addVideo($video);

        $objectManager->persist($trick);
        $this->addReference('first-trick', $trick);

        // # End trick 1

        // # trick 2
        $trick = new Trick();

        $trick->setName('Nose Grab');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('grabs'));
        $trick->setDescription('This awesome grab will really show off your mastery of the park but is easy to achieve. In the sixth of Ninja Academy’s snowboard skills series, Matt Barlow demonstrates how.

If you have basic grabs down, such as the indy grab (where you grab the toe-side edge between the feet with your back hand) then you’re ready to work your way up the board and try a nose grab.

 Ninja Academy Snowboaring skills video
Grabbing the tip of the board while maintaining the perfect position
For this trick it’s crucial to focus on bringing the nose of the board to your hand rather than trying to reach down for the board – bending your front leg and straightening your rear leg will bring the tipof the board towards you.

Once you’re comfortable with this move, try reversing it for a tail grab. This time pull the board up by bending your
back leg and straightening your front leg, then grab the tail of the board with your back hand.');
        $trick->setFrontPicture('front_nose_grab.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('nose_grab'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('M-W7Pmo-YMY');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 2

        // # trick 3
        $trick = new Trick();

        $trick->setName('Truck Driver');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('grabs'));
        $trick->setDescription('It’s actually a lil easier that it looks I think. I’m the LEAST flexible person I know, but truck drivers are my favorite bc they’re easy and look nice. 

Once you pop off the jump, put your legs straight but whip your ski tips towards you... think of it as flicking your toes forward and your boot and skis follow.
move your chest slightly towards your skis also, it will keep you body straight but gets your hands closer to grabbing. 

You’ll be able to grab about mid ski (don’t stress grabbing the tips if you’re just starting, you need to get comfortable first). 
Even if you get your finger tips to barely touch the sides of your skis, you get that feel and can keep trying to get your fingers around the ski to actually grab. 

I find if have a medium crouch when i go to take off from the jump, it’s easier to get the skis up and in arms reach.');
        $trick->setFrontPicture('front_truck_driver.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('truck_driver'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('FVZoGFB7aVM');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 3

        // # trick 4
        $trick = new Trick();

        $trick->setName('Front Flips');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('flips'));
        $trick->setDescription('A more technical and interesting frontflip.
1.
Find a jump with a sharp bend. Ride up to to the bend at a relatively slow speed. It should seem like you\'re going too slow to do a frontflip.
2.
In one fluid movement, quickly lean forward, bearing down on the nose of the left ski and pushing it in, and throw your right leg back as far you can. Now the nose should spring you into a forward flip.
3.
Raise your knees to your chest and wait for your landing.
4.
If you have going all in, find some soft snow with a track leading up to it, and do some half frontflips into it, landing on your back.');
        $trick->setFrontPicture('front_front_flips.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('front_flips'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('_ZLzckGPMPU');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 4

        // # trick 5
        $trick = new Trick();

        $trick->setName('Back Flips');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('flips'));
        $trick->setDescription('Wildcat - cartwheel style. Dump your hip and your shoulder hard over your tail. Easy to get a fast flip, can be stylish with a fs tweaked melon grab.

Layback - (Check out the link in my signature...thats a true backflip in my opinion) Same as a wild cat except you are staring in front of you and you gradually shift your weigh back up the jump then finally dump your back hip over your tail and look at the snow. Should feel like you quickly stretch out your side, then you kinda kick your tail out and float. All style in this one.

Backroll - Like the lay back, but instead of backflipping over your tail, you back flip over your heel edge. Way sketchier in my opinion because you have a good chance of initiating a slight spinning rotation AND you may not pop right thus landing on your tail in the landing.

I recommend doing backflips on jumps that you know that you can get a floaty 3 or a 540 off of. If I can 540 something without having to chuck too hard, I know I can backflip it. If you cant 3 with comfort, then dont backflip. Because that just states you aren\'t comfortable enough with a snowboard.

I highly reccomend doing wild cat flips at first, you will get a quicker rotation and you dont need a large sized jump. A lippy jump that only sends you 5\' is perfect. Quick to build as well. If you\'re in the park, then it\'s all up to you, find something lippy because you\'ll have more success in initiating a comfortable flip. When doing you\'re approaching the jump, look forwards but lean back more and more as you ride up the jump and then mildly punch for your flip once you reach the lip. Its less strain and it is much safer than to immidiately throw your body right as you are almost off the jump, because you can easily flip too early or too late. Too early will result in hitting your head in the jump (which is rare), too late will result in under rotation and belly floppin/ smashing your head (belly flops are common to see, smashing your head doesnt have a lot of chance, but its probable)');
        $trick->setFrontPicture('front_back_flips.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('back_flips'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('5bpzng08nzk');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 5

        // # trick 6
        $trick = new Trick();

        $trick->setName('Slide on Rail');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('slides'));
        $trick->setDescription('One aspect of Snowboarding Rails and Snowboard Jib Tricks that really confuses people sometimes is the way that rotations onto rails can be names the opposite of rotations off of jumps. In fact I was totally confused on this subject until just a few years ago. Again, as it should, the terminology comes from Street Style Skateboarding. 

While some sliders, rails and boxes especially in public terrain parks are set up with a take off that leads straight over the front end of the sliding surface, that is really more beginner or intermediate in terms of urban or skate style riding. Most of the time in sliding rails the skater would come at a slight angle and hop onto the feature from the side. This is where the confusion comes into play.

In urban or street style rail sliding the names of most tricks are based upon which side of the rail or box the rider jumps on from, and is not determined by the direction of rotation while sliding.');
        $trick->setFrontPicture('front_slide_on_rail.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('slide_on_rail'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('NeY6sSsbbZw');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 6

        // # trick 7
        $trick = new Trick();

        $trick->setName('BackSide Air');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('old-school'));
        $trick->setDescription('One aspect of Snowboarding Rails and Snowboard Jib Tricks that really confuses people sometimes is the way that rotations onto rails can be names the opposite of rotations off of jumps. In fact I was totally confused on this subject until just a few years ago. Again, as it should, the terminology comes from Street Style Skateboarding. 

While some sliders, rails and boxes especially in public terrain parks are set up with a take off that leads straight over the front end of the sliding surface, that is really more beginner or intermediate in terms of urban or skate style riding. Most of the time in sliding rails the skater would come at a slight angle and hop onto the feature from the side. This is where the confusion comes into play.

In urban or street style rail sliding the names of most tricks are based upon which side of the rail or box the rider jumps on from, and is not determined by the direction of rotation while sliding.');
        $trick->setFrontPicture('front_backside_air.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('backside_air'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('gV_s0_lfkgg');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 7

        // # trick 8
        $trick = new Trick();

        $trick->setName('Method Air');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('old-school'));
        $trick->setDescription('The method was dreamed up by skateboarder Neil Blender – a vert-riding legend who entered a highest air contest in California in 1985 with rules stating that the air would be measured from the lowest point of the rider’s body or board. So Neil grabbed his board and arched his back to squeeze as much height out of the trick as he could. He reckoned that this was his ‘method’ for winning the contest, and the name stuck.

They’re not super easy to do – especially with real style – but practice them and you’ll have an absolute classic move in your back pocket. And don’t forget, the method has outlasted every fashion and looks set to be the definitive snowboarding trick for many more years to come. Best get learning then…');
        $trick->setFrontPicture('front_method_air.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('method_air'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('_Cfssjuv0Zg');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 8

        // # trick 9
        $trick = new Trick();

        $trick->setName('Japan Air');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('old-school'));
        $trick->setDescription('Front hand grabs over the front leg and grabs the toe edge between the bindings. Front leg is usually pushed down and the board is pulled behind the rider slightly. Usually the nose is pushed higher than the tail on straight airs with Japan as you straighten the back leg downwards and pull the board upwards.

The Japan Air has become a true classic. It is a grab that can be tweaked like crazy and when done right (usually with backside spins and McTwists) it is mega. Originally invented by skateboarder Tony Hawk, the Japan Air has been frequently seen in snowboard circles in recent years. Nicolas Müller sent the Japan to another level when he did it with a Backside 720 and he went basically upside down while tweaking it out like crazy. Since then, Bode Merrill, went one better and went and landed the Back 7 Japan with one foot in his binders! Terje Haakonsen and Kazu Kokubo are also due credit for their Japan McTwists.

WHY JAPAN?
Because it’s tweakable. Tweaking and poking tricks is what shows you have control in your riding, and having control in the air is usually what makes a trick feel good!

HOW DO I MAKE THIS TRICK LOOK GOOD?
Make sure you get your hand onto your toe edge and keep your grab between the bindings. Because your leg can get in the way a little bit, it is easy to ruin this trick by grabbing your boot. Boot grabs aren’t rad, so keep that hand in between the bindings and, seriously think about stretching before wrenching a good tweak on this bad boy.');
        $trick->setFrontPicture('front_japan_air.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('japan_air'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('CzDjM7h_Fwo');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 9

        // # trick 10
        $trick = new Trick();

        $trick->setName('Rocket Air');
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setCategory($this->getReference('old-school'));
        $trick->setDescription('Before you challenge this trick, be sure to check the following points in advance.

- You can do straight air with stability.
- In the air with stability, you can do the following two movements at same time: 
Pull your foreleg up. 
Extend your hind leg. (Without a grab.) 
- You have plenty of time in the air to show off style. 
- In order to perform this grab trick, you already can do the correct movements on a level surface without your board attached. 
 
If you have a solid grasp on these things, you can probably learn this trick fairly quick!
Let\'s jump right into it!  
Sequence by sequence, I\'m going to explain the “Rocket Grab.”');
        $trick->setFrontPicture('front_rocket_air.jpg');


        for ($i = 1; $i <= 5; $i++) {
            $picture = new Picture();
            $picture->setTrick($trick);
            $picture->setFile('rocket_air'.$i.'.jpg');
            $trick->addPicture($picture);
        }

        $video = new Video();
        $video->setTrick($trick);
        $video->setUrl('ySVGdt_hom4');

        $trick->addVideo($video);

        $objectManager->persist($trick);

        # End Trick 10

        $objectManager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }

}