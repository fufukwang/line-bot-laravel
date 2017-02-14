#使用方式 打指令 make send id="sendId" msg="message"
#碰碰胡 Id: C53cd7ba8d1277455e8fdffd8687b7f8c
#chiuko Id: U7cbf49ac38f334e5977af0d737c5bae0

send:
	curl -X POST "https://line-bot-laravel.herokuapp.com/api/sendMsg" -d "id=${id}&msg=${msg}"