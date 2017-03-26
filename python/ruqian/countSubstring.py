from Bio import Seq
from Bio import SeqIO
import re

for seq_record in SeqIO.parse('ecoli.fa','fasta'):
    print len(seq_record.seq)
    query = seq_record.seq[3:19]
    print re.finditer(query,seq_record.seq)
