from Bio.Seq import Seq
from Bio import SeqIO
import sys
import regex as re
import timeit

start = timeit.default_timer()

# read filename and integer k from the command line
#filename = sys.argv[1]
#k = int(sys.argv[2])
filename = "ecoli.fa"
k = 10
for seq_record in SeqIO.parse(filename, "fasta"):
    map_dict = {}
    map = []
    reference = seq_record.seq
    len_reference = len(reference)
    for i in range(0, len_reference - k + 1):
        map.append(0)
        if reference[i:i + k] in map_dict:
            map_dict[reference[i:i + k]].append(i)
        else:
            readReverse = reference[i:i + k].reverse_complement()
            if readReverse in map_dict:
                map_dict[readReverse].append(i)
            else:
                map_dict[reference[i:i + k]] = [i]
    wiggle = open("testNew" + str(k) + ".wig", 'w');
    wiggle.write('fixedStep  chrom=' + seq_record.id + ' start=1')

    for keys in map_dict:
        l = len(map_dict[keys])
        for i in map_dict[keys]:
            map[i] = l


for n in map:
    wiggle.write("\n")
    wiggle.write("%.2f" %float(1/float(n)))
wiggle.close()

    #print ["%.2f" %float(1/float(n)) for n in map]


end = timeit.default_timer()
#print map_dict
#print map
print end - start

