<?php

use Illuminate\Database\Seeder;
use App\Sermon;

class SermonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $description = 'Other people look at us and see our weaknesses and flaws. God looks at us and sees our potential – through His presence with us. After the Midianites oppressed Israel for several years, God called Gideon to raise an army and deliver the people. But, as God significantly reduced the size of Gideon’s army, He taught Gideon – and us – that the battle belongs to the Lord, and that only He deserves the glory.';

        $notes = 'Gideon: Unlikely Hero

Judges 6:1 – 7:22


The period of the judges

· After Joshua died, the people of Israel were oppressed multiple times by the nations (the “ites”) they had failed to drive out of the land.

· Soon there arose a generation that did not know or follow the Lord. (And the same can happen today if we fail to teach the next generation).

· A summary statement at the end of Judges describes the state of the nation: “Everyone did what was right in his own eyes.” No nation, family, or individual can survive without moral absolutes.

Gideon’s circumstances

· When we meet Gideon, he is fearful and cowardly, and is hiding in a winepress where he can thresh grain without being seen by the Midianites.

· The angel of the Lord appears to Gideon and tells him God is with him, salutes him as a “mighty man of valor,” and commissions him to lead the people to defeat the Midianites.

· Gideon responds that his family is the “least in Manasseh,” and that he is “the youngest in my father’s house” – and therefore not a good candidate to accomplish what God wants him to do.

· But the angel replies that the Lord will be with Gideon and give him the victory.

Gideon’s conversion

· Gideon later responds with an act of worship as he prepares an offering that is accepted by God.

· Gideon also obeys the instruction to tear down the altar of Baal and build an altar to the Lord.

· Gideon “tests” God twice to confirm that God will truly be with him. This is NOT how we are to discern God’s will for our lives, but God allowed this to affirm Gideon’s call.

Gideon’s conquest

· God reduces Israel’s army from 32,000 (General Gideon)– to 10,000 (Captain Gideon) – to 300 (Sergeant Gideon), but He assures Gideon that He will deliver the people with this small band of men.

· To encourage Gideon, God tells him to visit the Midianite camp by night. There, he overhears that the Midianites are fearful and that they believe God will give the victory to Gideon.

· Despite being outnumbered 450:1, God enables this “barley cake soldier” to develop and execute a victorious strategy against the Midianites.

The application for today: Use that which is in your hand!

· Whatever you have, and wherever you are, God can use you to serve Him and to be victorious. What is in your hand?

Think it over

· Do you ever have trouble trusting in the promises of God?

· Have there been times in your life when you felt God calling you to do something you felt totally inadequate for? How did God strengthen you? How did He encourage you?

· What gifts has God given you to serve Him with? In other words, “What is in your hand?” Commit now to trust Him to empower you to use it for His glory!';

        $sermons = [
            [
                'title' => 'The Gospel of Mark',
                'vimeo_id' => '330889092',
                'duration' => '2400',
                'image' => 'https://i.vimeocdn.com/video/776114334_1920x1080.jpg',
                'publish_on' => '2019-04-07 05:00:00',
                'description' => $description,
                'notes' => $notes
            ],
        	[
	        	'title' => 'Entrance to the Kingdom',
	        	'vimeo_id' => '330889095',
	        	'duration' => '2400',
	        	'image' => 'https://i.vimeocdn.com/video/776114334_1920x1080.jpg',
	        	'publish_on' => '2019-04-14 05:00:00',
                'description' => $description,
                'notes' => $notes
        	],
            [
                'title' => 'The Great I Am',
                'vimeo_id' => '330889096',
                'duration' => '2400',
                'image' => 'https://i.vimeocdn.com/video/776114334_1920x1080.jpg',
                'publish_on' => '2019-04-19 13:00:00',
                'description' => $description,
                'notes' => $notes
            ],
            [
                'title' => 'Fisher of Men',
                'vimeo_id' => '331616357',
                'duration' => '2400',
                'image' => 'https://i.vimeocdn.com/video/776114334_1920x1080.jpg',
                'publish_on' => '2019-04-21 05:00:00',
                'description' => $description,
                'notes' => $notes
            ]
        ];

		foreach($sermons as $sermon) {
			Sermon::create($sermon);
		}
    }
}
