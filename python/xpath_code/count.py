from Bio.Seq import Seq
from Bio import SeqIO
import regex as re
import sys
import timeit

start = timeit.default_timer()


def countRepeats(read, readReverse):
    i = 0
    j = 0
    global reference
    global referenceReverse
    i = len(re.findall(str(read), str(reference), overlapped=True))
    if read != readReverse:
        j = len(re.findall(str(read), str(referenceReverse), overlapped=True))
    return i + j



# read filename and integer k from the command line
#filename = sys.argv[1]
#k = int(sys.argv[2]);
map_dict = {}
map = []
filename = "ecoli.fa"
k=10
for seq_record in SeqIO.parse(filename, "fasta"):
    global reference
    global referenceReverse
    reference = seq_record.seq
    referenceReverse= reference.reverse_complement()

    len_reference = len(reference)
    for i in range(0, len_reference - k + 1):
        map.append(0)
        readReverse = reference[i:i + k].reverse_complement()
        if reference[i:i + k] in map_dict:
            map[i] = map_dict[reference[i:i + k]]
        elif readReverse in map_dict:
            map[i] = map_dict[readReverse]
        else:
            map_dict[reference[i:i + k]] = countRepeats(reference[i:i + k],readReverse)
            map[i] = map_dict[reference[i:i + k]]

#print map_dict
    wiggle = open(seq_record.id + str(k)+".wig", 'w')
    wiggle.write('fixedStep  chrom=' + seq_record.id + ' start=1')

    #print ["%.2f" %float(1/float(n)) for n in map]
    for n in map:
        wiggle.write("\n")
        wiggle.write("%.2f" % float(1 / float(n)))
    wiggle.close()

end = timeit.default_timer()


print end - start
