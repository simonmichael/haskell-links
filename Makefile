# publish / deploy

# publish to github only
p:
	@git push github

# forcibly
P:
	@git push -f github

# deploy to production only
d:
	@git push prod
	@ssh joyful.com 'cd src/haskell-links && git reset --hard && git fetch github'

# forcibly
D:
	@git push -f prod
	@ssh joyful.com 'cd src/haskell-links && git reset --hard && git fetch github'

# publish and deploy
pd:
	@git push github
	@ssh joyful.com "cd src/haskell-links && git pull github"
	@git fetch prod

# forcibly
PD: P D
