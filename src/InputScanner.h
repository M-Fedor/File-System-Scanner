#ifndef InputScanner_h
#define InputScanner_h

#include "Input.h"
#include <cstring>
#include <dirent.h>
#include <sstream>
#include <vector>

/* Class implements file-system recursive iterator */
class InputScanner : public Input
{
public:
  InputScanner(std::string &rootDirectory, const char *pattern = NULL);
  InputScanner(std::vector<std::string> &rootDirectories, const char *pattern = NULL);
  ~InputScanner();

  int init();
  int inputNextFile(std::ifstream &fDescriptor, std::string &pathName);

private:
  int findNextFDRec(std::ifstream &fDescriptor, std::string &pathName);
  void printErr(int errNUm, const std::ostringstream &errInfo);

  std::vector<std::string> absolutePaths;
  std::vector<DIR *> directoryStreams;
  std::vector<std::string> rootDirectories;
};

#endif