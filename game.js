//enchant.js本体やクラスをエクスポートする
enchant();

window.onload = function(){
    
  GetRanking(); 
  SendScore("キング2",9999);
    //ゲームオブジェクトを作成する
    core = new Core(320,320);

    core.fps = 30;
    //スコアを格納するプロパティを設定する
    core.score = 0;
    //制限時間を格納するプロパティを設定する
    core.limitTime = 60;

    core.createflg = false;

    core.gun;

    //ゲームで使用する画像ファイルを指定する
    core.preload('shooter.png','bricks.png','backImage.jpg','gun.png','Enemyshooter.png','timeup.png','Enemygun.png');

    
    //ファイル(素材など）のプリロードが完了したときに実行される関数
    core.onload = function(){
      
      
      //物理シミュレーションを行うための仮想世界を作成する
      physicsWorld = new PhysicsWorld(0,9.8);

      var gun;
      var guns = [];
      //バックグラウンド画像を表示するスプライトを作成する
      var bg = new Sprite(320,320);
      //bg.backgroundColor = '#000000';
      bg.image = core.assets['backImage.jpg'];
      bg.addEventListener('touchstart',function(e){
        gun = new Gun(player.x,player.y,enemy,scoreLabel);
        
      });
      
      
      //左右の壁を作成する
      for(var i = 0; i < 20; i++){
        //左の壁
        //壁（静止している四角形の物理シミュレーション用スプライト）を作成する
        var wallLeft =
        new PhyBoxSprite(16,16,enchant.box2d.STATIC_SPRITE,1.0,0.5,0.5,true);
        //画像に「bricks.png」を設定する
        wallLeft.image = core.assets['bricks.png'];
        //表示するフレームの番号を設定する
        wallLeft.frame = 20;
        //表示位置（座標）を設定する
        wallLeft.position = {x: 8, y: i*16 + 8};
        core.rootScene.addChild(wallLeft);

        //右の壁
        var wallRight =
        new PhyBoxSprite(16,16,enchant.box2d.STATIC_SPRITE,1.0,0.5,0.5,true);
        wallRight.image = core.assets['bricks.png'];
        wallRight.frame = 20;
        wallRight.position = {x: 320 -8, y: i*16 + 8};
        core.rootScene.addChild(wallRight);
      }
      core.rootScene.addChild(bg);
      //プレイヤーを表示するスプライトを作成する
      //引数は初期位置のxy座標を指定する
      var player = new Player(136,272);

      var elementWidth = document.getElementById("enchant-stage").clientWidth;
      var rate = core.width /elementWidth;//画面比率を計算
      //指定したIDのタグにマウスハンドラ登録
      document.getElementById("enchant-stage").onmousemove = 
        function(e){
        //
        player.x = e.pageX * rate;
        if(player.x < 0){
          player.x = 0;
        }else if(320 - player.width < player.x){
          player.x = 320 - player.width
        }
      };
      
      //敵生成処理
      var enemy = new Enemy(136,20);
      enemy.addEventListener('enterframe',function(){
        //1秒間隔で実行する処理
        if(core.frame % core.fps == 0){
          //敵の弾生成処理
          var enemygun = new EnemyGun(this.x + this.width / 2,this.y + 51,player,scoreLabel);
        }

      });
      
      //rootSceneの「enterframe」イベントリスナ
      core.rootScene.addEventListener('enterframe',function(e){
       
        
        //1秒間隔で実行する処理
        if(core.frame % core.fps == 0){
          //制限時間をカウントダウンして、表示を更新する
          core.limitTime--;
          timeLabel.text = 'TIME:' + core.limitTime;
          //制限時間が「０」なら、タイムアップ画像を表示してゲーム終了
          if(core.limitTime == 0)core.end(null,null,core.assets['timeup.png']);
        }
        
      });
      
      //制限時間（残り時間）をフォントで表示するラベルを作成する
      //引数はラベル表示位置のxy座標
      var timeLabel = new MutableText(192,0);
      //表示する文字列の初期設定
      timeLabel.text = 'TIME:' + core.limitTime;
      core.rootScene.addChild(timeLabel);

      //スコアをフォントで表示するラベルを作成する
      //引数はラベル表示位置のxy座標
      var scoreLabel = new ScoreLabel(20,0);
      //スコアの初期置
      scoreLabel.score = 0;
      //イージング表示なしに設定する
      scoreLabel.easing = 0;
      core.rootScene.addChild(scoreLabel);
    }

    //ゲームスタート
    core.start();
}

var Gun = enchant.Class.create(enchant.Sprite,{
    initialize: function(x,y,enemy,scoreLabel){
      console.log("touch");
      enchant.Sprite.call(this,10,32);
      var image = new Surface(10,32);
      image.draw(core.assets['gun.png'],0,0,172,557,0,0,10,32);
      this.image = image;
      this.x = x;
      this.y = y;
      

      this.addEventListener('enterframe',function(){
        this.y -= 4;
        
        
        if(enemy.within(this,15)){
          //このスプライトをrootSceneから削除する
          core.rootScene.removeChild(this);
          //このスプライトを削除する
          delete this;
          
          core.score += 50;
          scoreLabel.score = core.score; 
        }
        
         if(this.y <= 10){
          //このスプライトをrootSceneから削除する
          core.rootScene.removeChild(this);
          //このスプライトを削除する
          delete this;
         }
       
      });
      core.rootScene.addChild(this);
  }
});

//プレイヤーのスプライトを作成するクラス
var Player = enchant.Class.create(enchant.Sprite,{
    //「initialize」メソッド（コンストラクタ）
    initialize: function(x,y){
        //継承元をコール
        this.originWidth = 50;
        this.originHeight = 40;
        enchant.Sprite.call(this,this.originWidth,this.originHeight);
        //画像に「player2.png」を設定する
        var image = new Surface(this.originWidth,this.originHeight);
        image.draw(core.assets['shooter.png'],0,0,393,392,0,0,this.originWidth,this.originHeight);
        this.image = image;
        this.x = x; //x座標
        this.y = y; //y座標
        //1回の移動量を設定する
        this.speed = 2;
       
        //「enterframe」イベントリスナ
        this.addEventListener('enterframe',function(){

            //左ボタンが押されたら、かつx座標が「0」より大きいなら、左に移動する
            if(core.input.left && this.x > 0){
              this.x -= this.speed;
            }
            //右ボタンが押されたら、かつx座標が「320 - このスプライトの幅」より小さいなら、右に移動する
            else if(core.input.right && this.x < 320 -this.width){
              this.x += this.speed;
            }
        });
       
        core.rootScene.addChild(this);
  } 
});

var Enemy = enchant.Class.create(enchant.Sprite,{
  initialize: function(x,y){
    enchant.Sprite.call(this,50,52);
    var enemyimage = new Surface(50,52);
    enemyimage.draw(core.assets['Enemyshooter.png'],0,0,302,320,0,0,50,52);
    this.image = enemyimage;
    this.x = x;
    this.y = y;
    this.vx = 4;
    //scoreLabel.score = core.score;
    this.addEventListener('enterframe',function(){
      this.x += this.vx;
      if(this.x > 320 - this.width || this.x < 0)this.vx *= -1;
      // if(core.frame % core.fps == 0){
      //   var enemygun = new EnemyGun(this.x + this.width / 2,this.y + 51);
      // }
    });
    core.rootScene.addChild(this);
  }
});

var EnemyGun = enchant.Class.create(enchant.Sprite,{
  initialize: function(x,y,player,scoreLabel){
    enchant.Sprite.call(this,13,16);
    var enemygunimage = new Surface(13,16);
    enemygunimage.draw(core.assets['Enemygun.png'],0,0,130,167,0,0,13,16);
    this.image = enemygunimage;
    this.x = x;
    this.y = y;
    this.addEventListener('enterframe',function(){
      this.y += 4;
      if(player.within(this,13)){
        //このスプライトをrootSceneから削除する
        core.rootScene.removeChild(this);
        //このスプライトを削除する
        delete this;
        
        core.score -= 10;
        scoreLabel.score = core.score; 
      } 
      if(this.y >= 310){
        //このスプライトをrootSceneから削除する
        core.rootScene.removeChild(this);
        //このスプライトを削除する
        delete this;
       } 
    });
    core.rootScene.addChild(this);
  }
});

//ランキング取得メソッド
var GetRanking = function(){
  var xmlhttp = new XMLHttpRequest();
  //ローカルファイルのPHPを実行
  xmlhttp.open("GET","db2/get_ranking.php",true);
  xmlhttp.responseType = 'text';
  //PHP読み込み時の処理
  xmlhttp.onload = function (){
    if(xmlhttp.readyState === xmlhttp.DONE){
      if(xmlhttp.status === 200){//成功ステータスコード
        //通信成功時の処理
        console.log(xmlhttp.responseText);
      }
    }
  }
  xmlhttp.send();//通信開始
}

var SendScore = function(name,score){
  var param = "name=" + name;
  param += "&";
  param += "score=" + score;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET","db2/add_score.php?" + param, true);
  xmlhttp.responseType = 'text';
  xmlhttp.onload = function(){
    if(xmlhttp.readyState === xmlhttp.DONE){
      if(xmlhttp.status === 200){
        //通信成功時の処理
        console.log(xmlhttp.responseText);
      }else{
        console.log("status=" + xmlhttp.status);
      }
    }
  }
  xmlhttp.send();
}