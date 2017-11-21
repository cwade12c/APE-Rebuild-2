"""
Used to brute force check for collisions
"""

import sys
import hashlib

hashingAlg = hashlib.md5
# keep in mind the string checked is converted to hex
hashingLength = 12

def ewuIDs():
	# id format: 00 ### ###
	for x in range(10 ** 6):
		yield "{0:08}".format(x)

if __name__ == '__main__':
	print('Checking for collisions with {}, truncated to {} bytes'.format(hashingAlg.__name__, hashingLength))
	hashes = {}
	for id in ewuIDs():
		hash = hashingAlg(str.encode(id)).hexdigest()[:hashingLength]
		if hash in hashes:
			originalID = hashes[hash]
			print('Found collision({}), ID1: {}, ID2: {}'.format(hash, id, originalID), file=sys.stderr)
		hashes[hash] = id;
	print('No collisions detected, save')