echo Stop all running containers
docker stop $(docker ps -aq)
echo Remove all containers
docker rm $(docker ps -aq)
echo Remove all images
docker rmi $(docker images -q)
