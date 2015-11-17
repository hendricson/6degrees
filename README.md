# Six degrees of separation algorithm along with the PHP implementation

Six degrees of separation is the theory that anyone on the planet can be connected to any other person on the planet through a chain of acquaintances that has no more than five intermediaries. This theory can be tested on relatively small set of people connected with a directed graph, representing users of a social network. The algorithm builds "a friend of a friend" chain, which connects any two users in the network. The algorithm below is what I came up with for the social network I was building.

Demo: [http://hendricson.com/demo/6degrees/](http://hendricson.com/demo/6degrees/)

## ALGORITHM

As an input, the algorithm takes ID of a person we want to build a connection chain from (Id1) and ID of the person to whom we need to build a chain (Id2). It's assumed that we know whom each user of the network has befriended (i.e., we know 1st circle of connection for each user as depicted in the directed graph [here](http://hendricson.com/demo/6degrees/images/social_graph.png)). 

### Part 1: Do some preliminary work by building circles of connection

*Step 1.1* Generate circle 2: for each user in the 1st circle of Id1, add IDs of their friends to the 2nd circle of Id1, eliminating Id1 himself and those IDs that are already in the 1st circle of Id1.

*Step 1.2* Generate circle 3: for each user in the 2nd circle of Id1, add IDs of their friends to the 3d circle of Id1, eliminating Id1 himself and those IDs that are already in the previous circles of Id1.

...

*Step 1.N-1* Generate circle N

### Part 2: Generate a connection chain

*Step 2.1* See if Nth circle of Id1 intersects with the 1..N circle of Id2. If it does, take the 1st element from the intersection (IdI), save the current circle order of Id1 to L, and the circle order of Id2 to K, and follow to Step 2.2. If it doesn't, repeat this step for the smaller circle of Id1.

*Step 2.2* See if (L-1)th circle of Id1 intersects with the 1st circle of IdI. If it does, take the 1st element from the intersection (IdI1). If it doesn't, repeat Step 2.2 for (L-2)th circle of Id1 and the 2nd circle of IdI.

As a result of Step 2.2, the following sequence will be generated: Id1, ..., IdI1, IdI.

*Step 2.3* See if (K-1)th circle of Id2 intersects with the 1st circle of IdI. If it does, take the 1st element from the intersection (IdI2). If it doesn't, repeat Step 2.3 for (K-2)th circle of Id2 and the 2nd circle of IdI.

As a result of Step 2.3, the following sequence will be generated: IdI, IdI2, ..., Id2.

*Step 2.4* Merging the two sequences generated in steps 2.2 and 2.3, the resulting chain will be formed: Id1, ..., IdI1, IdI, IdI2, ..., Id2.

## SETTING UP DEMO WEBSITE

1. Make a local copy of the 6degrees

2. Configure database settings at
*config.php*

3. Click "Rebuild the graph" on the main page of the demo site to generate connection circles for the set of users.


